import React from 'react';
import { Inertia } from '@inertiajs/inertia';

type Props = { open: boolean; id: number | null; onClose: () => void };

export default function DeleteModal({ open, id, onClose }: Props) {
  if (!open || !id) return null;

  function destroy() {
    Inertia.delete(route('station.print.destroy', id));
    onClose();
  }

  return (
    <div className="fixed inset-0 flex items-center justify-center z-50">
      <div className="bg-white p-6 rounded shadow">
        <h3 className="font-bold">Hapus file?</h3>
        <div className="mt-4 flex gap-2">
          <button onClick={destroy} className="btn btn-danger">Delete</button>
          <button onClick={onClose} className="btn">Cancel</button>
        </div>
      </div>
    </div>
  );
}
