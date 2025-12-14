#!/bin/bash
# SIPAP Installation & Verification Script (Linux/macOS)

echo "=================================="
echo "  SIPAP v1.0 - Setup Script"
echo "=================================="
echo ""

# Check folder exists
if [ ! -d "/Applications/XAMPP/xamppfiles/htdocs/sipap" ] && [ ! -d "/opt/xampp/htdocs/sipap" ]; then
    echo "❌ Folder sipap tidak ditemukan!"
    echo "   Pastikan folder sipap ada di:"
    echo "   - /Applications/XAMPP/xamppfiles/htdocs/sipap (macOS)"
    echo "   - /opt/xampp/htdocs/sipap (Linux)"
    exit 1
fi

echo "✅ Folder sipap ditemukan"
echo ""

# Count files
PHP_COUNT=$(find . -name "*.php" | wc -l)
MD_COUNT=$(find . -name "*.md" | wc -l)
SQL_COUNT=$(find . -name "*.sql" | wc -l)

echo "📊 File Statistics:"
echo "   PHP Files: $PHP_COUNT"
echo "   Documentation: $MD_COUNT"
echo "   Database: $SQL_COUNT"
echo ""

echo "=================================="
echo "  ✅ SIPAP Siap Digunakan!"
echo "=================================="
echo ""
echo "Next Steps:"
echo "1. Import database.sql ke MySQL"
echo "2. Buka http://localhost/sipap di browser"
echo "3. Login dengan akun: admin / password"
echo ""
echo "Dokumentasi:"
echo "- START.md: Panduan cepat"
echo "- README.md: Dokumentasi lengkap"
echo "- INSTALASI.md: Panduan instalasi"
echo ""
