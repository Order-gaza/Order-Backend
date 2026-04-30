"""
Comprehensive Schema vs PHP Static Analyzer v2
Hardcodes table definitions from order_full_schema.sql to avoid regex parsing issues.
Scans all PHP files for INSERT, UPDATE, FROM, JOIN references and validates them.
"""
import os
import re
import sys

# ══════════════════════════════════════════════════════════════════════
# 1. HARDCODED SCHEMA (from order_full_schema.sql uploaded 2026-03-12)
# ══════════════════════════════════════════════════════════════════════
SCHEMA = {
    "activity_logs": ["id","user_id","action","entity_type","entity_id","description","ip_address","created_at"],
    "admin_sessions": ["id","user_id","token","ip_address","user_agent","expires_at","created_at"],
    "advertisements": ["id","title","description","image_url","vendor_id","target_url","sort_order","is_active","starts_at","ends_at","created_at"],
    "cart_items": ["id","user_id","menu_item_id","quantity","notes","created_at","updated_at"],
    "customer_addresses": ["id","user_id","label","address_line","latitude","longitude","phone","notes","is_default","created_at","updated_at"],
    "customer_profiles": ["id","user_id","profile_image_url","default_address_id","created_at","updated_at"],
    "delivery_zones": ["id","name","min_distance_km","max_distance_km","base_fee","nearby_extra_fee","far_extra_fee","is_active","created_at"],
    "email_verifications": ["id","email","otp","type","expires_at","verified","created_at"],
    "fee_config": ["id","active_model","flat_fee_amount","percentage_value","model_switch_date","updated_by","updated_at"],
    "menu_categories": ["id","vendor_id","name","sort_order","is_active","created_at"],
    "menu_items": ["id","category_id","vendor_id","name","description","base_price","discount_price","preparation_time","image_url","is_available","sort_order","created_at","updated_at"],
    "notifications": ["id","user_id","title","body","type","data","is_read","created_at"],
    "orders": ["id","order_number","customer_id","admin_id","branch_region","delivery_address","delivery_latitude","delivery_longitude","delivery_phone","subtotal","delivery_fee","total_amount","platform_fee","fee_model_used","payment_method","payment_status","status","customer_notes","cancellation_reason","confirmed_at","delivered_at","created_at","updated_at"],
    "order_items": ["id","order_vendor_id","menu_item_id","item_name","unit_price","quantity","line_total","special_instructions","created_at"],
    "order_status_history": ["id","order_id","from_status","to_status","changed_by","note","created_at"],
    "order_vendors": ["id","order_id","vendor_id","subtotal","vendor_notes","vendor_delivery_fee","vendor_net_amount","status","accepted_at","ready_at","created_at"],
    "platform_earnings": ["id","order_id","order_number","order_total","platform_fee","fee_model","fee_value_snapshot","is_collected","collected_at","earned_at"],
    "ratings": ["id","order_id","customer_id","vendor_id","score","comment","created_at"],
    "users": ["id","name","phone","email","password_hash","role","address","latitude","longitude","fcm_token","is_active","email_verification_token","email_verified_at","created_at","updated_at"],
    "vendors": ["id","user_id","name","type","description","logo_url","cover_url","phone","address","latitude","longitude","opening_time","closing_time","is_open","min_order_amount","estimated_delivery_time","operating_hours","status","rating","total_ratings","commission_rate","created_at","updated_at"],
}

# Known DB views (not tables, but valid to SELECT FROM)
KNOWN_VIEWS = {
    "view_admin_orders", "view_order_detail", "view_order_stats",
    "view_vendor_list", "view_vendor_orders",
    "view_earnings_ledger", "view_earnings_daily_summary",
}

# Words that appear after FROM/JOIN in PHP but are NOT table names (false positives)
FALSE_POSITIVE_WORDS = {
    "dual", "header", "server", "post", "database", "current", "authorization",
    "naseej", "request", "verifyauth", "detail", "view", "browser", "a", "cart",
    "zones", "information_schema",
}

backend_dir = os.path.abspath(os.path.join(os.path.dirname(os.path.abspath(__file__)), '..'))

# ══════════════════════════════════════════════════════════════════════
# 2. SCAN PHP FILES
# ══════════════════════════════════════════════════════════════════════
php_files = []
for root, _, files in os.walk(backend_dir):
    # Skip tests directory
    if os.sep + 'tests' in root or root.endswith('tests'):
        continue
    for f in files:
        if f.endswith('.php'):
            php_files.append(os.path.join(root, f))

print(f"Schema: {len(SCHEMA)} tables, scanning {len(php_files)} PHP files...\n")

errors = []

for fpath in sorted(php_files):
    try:
        with open(fpath, 'r', encoding='utf-8') as f:
            content = f.read()
    except:
        continue
    
    rel = os.path.relpath(fpath, backend_dir).replace('\\', '/')
    
    # ── CHECK INSERT INTO table (col1, col2, ...) ──
    for m in re.finditer(r'INSERT\s+INTO\s+`?(\w+)`?\s*\((.*?)\)', content, re.I | re.S):
        tbl = m.group(1).lower()
        if tbl not in SCHEMA:
            errors.append(f"[{rel}] INSERT into unknown table: `{tbl}`")
            continue
        raw_cols = m.group(2)
        cols = [c.strip().strip('`').strip().lower() for c in raw_cols.split(',')]
        cols = [c for c in cols if c and re.match(r'^[a-z_][a-z0-9_]*$', c)]
        for col in cols:
            if col not in SCHEMA[tbl]:
                errors.append(f"[{rel}] INSERT `{tbl}` has unknown column: `{col}`   (valid: {SCHEMA[tbl]})")

    # ── CHECK UPDATE table SET col = ... ──
    for m in re.finditer(r'UPDATE\s+`?(\w+)`?\s+SET\s+(.*?)(?:\s+WHERE|\s*"\s*\))', content, re.I | re.S):
        tbl = m.group(1).lower()
        if tbl not in SCHEMA:
            if tbl not in FALSE_POSITIVE_WORDS:
                errors.append(f"[{rel}] UPDATE unknown table: `{tbl}`")
            continue
        set_str = m.group(2)
        # Parse "col = ?, col2 = ?" 
        for piece in set_str.split(','):
            if '=' not in piece:
                continue
            col_part = piece.split('=')[0].strip().strip('`').lower()
            if '.' in col_part:
                col_part = col_part.split('.')[-1].strip()
            if re.match(r'^[a-z_][a-z0-9_]*$', col_part):
                if col_part not in SCHEMA[tbl]:
                    errors.append(f"[{rel}] UPDATE `{tbl}` sets unknown column: `{col_part}`")

    # ── CHECK FROM table / JOIN table ──
    for m in re.finditer(r'\bFROM\s+`?(\w+)`?', content, re.I):
        tbl = m.group(1).lower()
        if tbl in SCHEMA or tbl in KNOWN_VIEWS or tbl in FALSE_POSITIVE_WORDS:
            continue
        errors.append(f"[{rel}] SELECT FROM unknown table/view: `{tbl}`")

    for m in re.finditer(r'\bJOIN\s+`?(\w+)`?', content, re.I):
        tbl = m.group(1).lower()
        if tbl in SCHEMA or tbl in KNOWN_VIEWS or tbl in FALSE_POSITIVE_WORDS:
            continue
        errors.append(f"[{rel}] JOIN unknown table/view: `{tbl}`")

# ══════════════════════════════════════════════════════════════════════
# 3. REPORT
# ══════════════════════════════════════════════════════════════════════
unique_errors = sorted(set(errors))

if not unique_errors:
    print("SUCCESS: All PHP files are consistent with the DB schema!")
else:
    print(f"FOUND {len(unique_errors)} REAL INCONSISTENCIES:\n")
    for e in unique_errors:
        print(f"  {e}")

print(f"\n--- Done. Checked {len(php_files)} files against {len(SCHEMA)} tables ---")
