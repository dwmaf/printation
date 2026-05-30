import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import OutletLayout from '@/Layouts/OutletLayout';
import { Plus, Edit, Trash, X, MonitorPlay } from 'lucide-react';
import { route } from 'ziggy-js';

interface StationUser {
    id: number;
    name: string;
    email: string;
}

interface Props {
    stations: StationUser[];
}

export default function IndexStation({ stations }: Props) {
    const [isAddModalOpen, setIsAddModalOpen] = useState(false);
    const [isEditModalOpen, setIsEditModalOpen] = useState(false);
    const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
    const [selectedStation, setSelectedStation] = useState<StationUser | null>(null);

    // Form Tambah
    const { 
        data: addData, setData: setAddData, post: postAdd, 
        processing: addProcessing, errors: addErrors, reset: resetAdd 
    } = useForm({
        name: '',
        email: '',
        password: '',
    });

    // Form Edit
    const { 
        data: editData, setData: setEditData, put: putEdit, 
        processing: editProcessing, errors: editErrors, reset: resetEdit 
    } = useForm({
        name: '',
        password: '',
    });

    const openAddModal = () => setIsAddModalOpen(true);
    const closeAddModal = () => { setIsAddModalOpen(false); resetAdd(); };
    
    const submitAdd = (e: React.SyntheticEvent) => {
        e.preventDefault();
        postAdd(route('outlet.stations.store'), { onSuccess: () => closeAddModal() });
    };

    const openEditModal = (station: StationUser) => {
        setSelectedStation(station);
        setEditData({ name: station.name, password: '' });
        setIsEditModalOpen(true);
    };
    
    const closeEditModal = () => { setIsEditModalOpen(false); setSelectedStation(null); resetEdit(); };

    const submitEdit = (e: React.SyntheticEvent) => {
        e.preventDefault();
        if (!selectedStation) return;
        putEdit(route('outlet.stations.update', selectedStation.id), { onSuccess: () => closeEditModal() });
    };

    const openDeleteModal = (station: StationUser) => { setSelectedStation(station); setIsDeleteModalOpen(true); };
    const closeDeleteModal = () => { setIsDeleteModalOpen(false); setSelectedStation(null); };

    const confirmDelete = () => {
        if (!selectedStation) return;
        router.delete(route('outlet.stations.destroy', selectedStation.id), { onSuccess: () => closeDeleteModal() });
    };

    return (
        <OutletLayout
            header={
                <h1 className="text-3xl text-gray-800 font-koulen uppercase tracking-wide">
                    Manajemen Station (Komputer Cetak)
                </h1>
            }
        >
            <Head title="Manajemen Station" />

            <div className="bg-white shadow-md rounded-xl p-6">
                <div className="flex justify-between items-center mb-6">
                    <h2 className="text-xl font-bold text-gray-800">Daftar Akun Station</h2>
                    <button
                        onClick={openAddModal}
                        className="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition duration-200"
                    >
                        <Plus className="w-5 h-5" />
                        Tambah Station
                    </button>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-gray-100 text-gray-700">
                                <th className="p-4 rounded-tl-lg font-semibold">Username Station</th>
                                <th className="p-4 font-semibold">Email Login</th>
                                <th className="p-4 rounded-tr-lg font-semibold text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {stations && stations.length > 0 ? (
                                stations.map((station) => (
                                    <tr key={station.id} className="border-b hover:bg-gray-50 transition border-gray-200">
                                        <td className="p-4 flex items-center gap-3 text-gray-800 font-medium">
                                            <div className="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                                                <MonitorPlay className="w-4 h-4" />
                                            </div>
                                            {station.name}
                                        </td>
                                        <td className="p-4 text-gray-600">{station.email}</td>
                                        <td className="p-4 flex gap-2 justify-center">
                                            <button
                                                onClick={() => openEditModal(station)}
                                                className="p-2 bg-yellow-100 text-yellow-600 hover:bg-yellow-200 rounded-lg transition"
                                                title="Edit Username/Password"
                                            >
                                                <Edit className="w-4 h-4" />
                                            </button>
                                            <button
                                                onClick={() => openDeleteModal(station)}
                                                className="p-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg transition"
                                                title="Hapus Station"
                                            >
                                                <Trash className="w-4 h-4" />
                                            </button>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={3} className="p-8 text-center text-gray-500">
                                        Belum ada Station Komputer yang ditambahkan untuk Outlet ini.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Modal Tambah */}
            {isAddModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
                        <div className="flex justify-between items-center p-4 border-b">
                            <h3 className="font-bold text-lg">Tambah Akun Station</h3>
                            <button onClick={closeAddModal} className="text-gray-400 hover:text-gray-600"><X className="w-5 h-5"/></button>
                        </div>
                        <form onSubmit={submitAdd} className="p-4">
                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Nama / Username Station</label>
                                    <input type="text" className="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500" value={addData.name} onChange={e => setAddData('name', e.target.value)} required placeholder="Contoh: Komputer Kanan" />
                                    {addErrors.name && <p className="text-red-500 text-xs mt-1">{addErrors.name}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Email Station</label>
                                    <input type="email" className="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500" value={addData.email} onChange={e => setAddData('email', e.target.value)} required />
                                    {addErrors.email && <p className="text-red-500 text-xs mt-1">{addErrors.email}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                    <input type="password" className="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500" value={addData.password} onChange={e => setAddData('password', e.target.value)} required minLength={8} />
                                    {addErrors.password && <p className="text-red-500 text-xs mt-1">{addErrors.password}</p>}
                                </div>
                            </div>
                            <div className="mt-6 flex justify-end gap-2">
                                <button type="button" onClick={closeAddModal} className="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Batal</button>
                                <button type="submit" disabled={addProcessing} className="px-4 py-2 text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg disabled:opacity-50">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* Modal Edit */}
            {isEditModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
                        <div className="flex justify-between items-center p-4 border-b">
                            <h3 className="font-bold text-lg">Edit Station</h3>
                            <button onClick={closeEditModal} className="text-gray-400 hover:text-gray-600"><X className="w-5 h-5"/></button>
                        </div>
                        <form onSubmit={submitEdit} className="p-4">
                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Nama Station</label>
                                    <input type="text" className="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500" value={editData.name} onChange={e => setEditData('name', e.target.value)} required />
                                    {editErrors.name && <p className="text-red-500 text-xs mt-1">{editErrors.name}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Password Baru (Biarkan kosong jika tidak diubah)</label>
                                    <input type="password" className="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500" value={editData.password} onChange={e => setEditData('password', e.target.value)} minLength={8} />
                                    {editErrors.password && <p className="text-red-500 text-xs mt-1">{editErrors.password}</p>}
                                </div>
                            </div>
                            <div className="mt-6 flex justify-end gap-2">
                                <button type="button" onClick={closeEditModal} className="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Batal</button>
                                <button type="submit" disabled={editProcessing} className="px-4 py-2 text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg disabled:opacity-50">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* Modal Hapus */}
            {isDeleteModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden p-6 text-center">
                        <div className="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4"><Trash className="w-8 h-8"/></div>
                        <h3 className="font-bold text-xl mb-2">Hapus Station?</h3>
                        <p className="text-gray-500 mb-6">Yakin ingin menghapus station <strong>{selectedStation?.name}</strong>? Komputer tidak bisa login lagi dengan akun ini.</p>
                        <div className="flex justify-center gap-3">
                            <button onClick={closeDeleteModal} className="px-6 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition font-medium">Batal</button>
                            <button onClick={confirmDelete} className="px-6 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg transition font-medium">Ya, Hapus</button>
                        </div>
                    </div>
                </div>
            )}
        </OutletLayout>
    );
}