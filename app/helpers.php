<?php

function formatCurrency($value) {
  // transform to indonesian currency format
  return "Rp " . number_format($value, 0, ',', '.');
}

function renderPaymentStatus($value) {
  switch ($value) {
    case 'UNPAID':
      return '<span class="badge bg-warning">Belum Dibayar</span>';
      break;
    case 'PAID':
      return '<span class="badge bg-success">Sudah Dibayar</span>';
      break;
    case 'PENDING':
      return '<span class="badge bg-info">Menunggu Pembayaran</span>';
      break;
    case 'EXPIRED':
      return '<span class="badge bg-danger">Kadaluarsa</span>';
      break;
    case 'FAILED':
      return '<span class="badge bg-danger">Gagal</span>';
      break;
    default:
      return '<span class="badge bg-secondary">Tidak Diketahui</span>';
      break;
  }
}
