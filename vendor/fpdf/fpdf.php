<?php

/**
 * FPDF Compatibility Shim
 * 
 * If the real FPDF library is not installed, this provides a basic
 * fallback so orders still process (without PDF generation).
 * 
 * To get full PDF generation, install FPDF:
 * 1. Download from http://www.fpdf.org or https://github.com/Setasign/FPDF
 * 2. Copy fpdf.php to vendor/fpdf/fpdf.php
 * 3. Delete this shim file
 */

if (!class_exists('FPDF')) {
    /**
     * Minimal FPDF stub - orders work but no PDF is generated.
     * Replace with real FPDF for PDF functionality.
     */
    class FPDF
    {
        protected $page = 0;
        protected $x = 15;
        protected $y = 15;
        protected $font = 'Arial';
        protected $font_size = 12;
        protected $content = '';
        protected $margins = ['left' => 15, 'right' => 15, 'top' => 15];

        public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4') {}
        public function AddPage($orientation = '', $size = '', $rotation = 0)
        {
            $this->page++;
        }
        public function SetFont($family, $style = '', $size = 0)
        {
            $this->font = $family;
            if ($size) $this->font_size = $size;
        }
        public function SetTextColor($r = 0, $g = 0, $b = 0) {}
        public function SetFillColor($r = 0, $g = 0, $b = 0) {}
        public function SetDrawColor($r = 0, $g = 0, $b = 0) {}
        public function SetLineWidth($width) {}
        public function SetMargins($left, $top, $right = -1)
        {
            $this->margins = ['left' => $left, 'top' => $top, 'right' => $right];
        }
        public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '') {}
        public function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false) {}
        public function Ln($h = '') {}
        public function GetY()
        {
            return $this->y;
        }
        public function GetX()
        {
            return $this->x;
        }
        public function SetXY($x, $y)
        {
            $this->x = $x;
            $this->y = $y;
        }
        public function Line($x1, $y1, $x2, $y2) {}
        public function Image($file, $x = null, $y = null, $w = 0, $h = 0, $type = '', $link = '') {}
        public function SetTitle($title, $isUTF8 = false) {}
        public function SetAuthor($author, $isUTF8 = false) {}

        public function Output($dest = '', $name = '', $isUTF8 = false)
        {
            if ($dest === 'F') {
                // Create a simple text-based order receipt instead
                $simple_content = "UNIQUE STUDIO - Order Receipt\n";
                $simple_content .= "Generated: " . date('Y-m-d H:i:s') . "\n";
                $simple_content .= "(Full PDF generation requires FPDF library)\n\n";
                $simple_content .= "Install FPDF: https://github.com/Setasign/FPDF\n";
                file_put_contents($name, $simple_content);
                return '';
            }
            return '';
        }
    }
}
