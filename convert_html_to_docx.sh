#!/usr/bin/env bash
# Script untuk mengkonversi file HTML ke DOCX menggunakan pandoc
# Jika pandoc belum install, script akan memberikan instruksi

echo "=========================================="
echo "KONVERSI HTML KE DOCX"
echo "=========================================="
echo ""

# Cek apakah file HTML ada
if [ ! -f "LAPORAN_AKHIR_AureliaBox.html" ]; then
    echo "❌ Error: File LAPORAN_AKHIR_AureliaBox.html tidak ditemukan"
    exit 1
fi

echo "📄 File HTML ditemukan: LAPORAN_AKHIR_AureliaBox.html"
echo ""

# Cek apakah pandoc terinstall
if command -v pandoc &> /dev/null; then
    echo "✅ Pandoc terdeteksi"
    echo ""
    echo "⏳ Mengkonversi ke DOCX..."
    
    # Konversi
    pandoc LAPORAN_AKHIR_AureliaBox.html -o LAPORAN_AKHIR_AureliaBox.docx
    
    if [ -f "LAPORAN_AKHIR_AureliaBox.docx" ]; then
        echo ""
        echo "✅ Konversi berhasil!"
        echo ""
        echo "📍 File DOCX: LAPORAN_AKHIR_AureliaBox.docx"
        ls -lh LAPORAN_AKHIR_AureliaBox.docx
    else
        echo "❌ Konversi gagal"
        exit 1
    fi
else
    echo "⚠️ Pandoc tidak terinstall"
    echo ""
    echo "📖 ALTERNATIF KONVERSI:"
    echo ""
    echo "1. Microsoft Word (PALING MUDAH):"
    echo "   - Buka file HTML dengan Microsoft Word"
    echo "   - File → Save As → Format: Word Document (.docx)"
    echo ""
    echo "2. Google Docs:"
    echo "   - Buka https://docs.google.com"
    echo "   - File → Buka → Upload HTML"
    echo "   - File → Download → Microsoft Word (.docx)"
    echo ""
    echo "3. LibreOffice:"
    echo "   - Buka file HTML dengan LibreOffice Writer"
    echo "   - File → Save As → Format: Word 2007-365 (.docx)"
    echo ""
    echo "4. Online Converter:"
    echo "   - Kunjungi: https://cloudconvert.com/html-to-docx"
    echo "   - Upload file HTML dan download hasil DOCX"
    echo ""
    echo "Untuk instalasi Pandoc:"
    echo "   - Windows: choco install pandoc (jika menggunakan Chocolatey)"
    echo "   - Linux: sudo apt-get install pandoc"
    echo "   - macOS: brew install pandoc"
    exit 1
fi
