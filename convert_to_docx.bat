@echo off
REM Script untuk konversi HTML ke DOCX menggunakan LibreOffice
REM Pastikan LibreOffice sudah diinstall

setlocal enabledelayedexpansion

echo Mengkonversi laporan HTML ke format DOCX...

REM Path ke file HTML
set HTML_FILE="%cd%\LAPORAN_AKHIR_AureliaBox.html"

REM Cek apakah file HTML sudah ada
if not exist %HTML_FILE% (
    echo Error: File HTML tidak ditemukan di %HTML_FILE%
    pause
    exit /b 1
)

REM Cek apakah LibreOffice terinstall
if exist "C:\Program Files\LibreOffice\program\soffice.exe" (
    set LIBREOFFICE="C:\Program Files\LibreOffice\program\soffice.exe"
) else if exist "C:\Program Files (x86)\LibreOffice\program\soffice.exe" (
    set LIBREOFFICE="C:\Program Files (x86)\LibreOffice\program\soffice.exe"
) else (
    echo LibreOffice tidak ditemukan. Gunakan metode alternatif.
    pause
    exit /b 1
)

echo LibreOffice ditemukan: %LIBREOFFICE%
echo.

REM Konversi ke DOCX
echo Memproses konversi...
%LIBREOFFICE% --headless --convert-to docx:%HTML_FILE%

echo.
echo Konversi selesai!
echo File DOCX akan tersimpan di folder yang sama
pause
