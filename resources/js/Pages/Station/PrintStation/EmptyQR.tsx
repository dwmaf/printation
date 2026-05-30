import React from 'react';

type Props = { qrData?: string | null };

export default function EmptyQR({ qrData }: Props) {
  if (!qrData) {
    return (
      <div className="p-4 bg-yellow-50 rounded">QR belum dibuat</div>
    );
  }

  return (
    <div className="p-4 bg-white rounded shadow">
      <div className="mb-4 text-sm text-gray-500">Scan QR untuk upload file</div>
      <div className="h-64 flex items-center justify-center" dangerouslySetInnerHTML={{ __html: qrData }} />
    </div>
  );
}
