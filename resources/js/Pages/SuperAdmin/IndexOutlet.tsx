import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Plus, Edit, Trash, X } from 'lucide-react';
import { route } from 'ziggy-js';

interface User {
    id: number;
    name: string;
    email: string;
}

interface Outlet {
    id: number;
    name: string;
    address: string;
    owner?: User;
}

interface Props {
    outlets: Outlet[];
}

export default function IndexOutlet({ outlets }: Props) {
    const [isAddModalOpen, setIsAddModalOpen] = useState(false);
    const [isEditModalOpen, setIsEditModalOpen] = useState(false);
    const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
    const [selectedOutlet, setSelectedOutlet] = useState<Outlet | null>(null);

    // Form untuk Tambah
    const { 
        data: addData, 
        setData: setAddData, 
        post: postAdd, 
        processing: addProcessing, 
        errors: addErrors, 
        reset: resetAdd 
    } = useForm({
        name: '',
        address: '',
        owner_name: '',
        owner_email: '',
        owner_password: '',
    });

    // Form untuk Edit
    const { 
        data: editData, 
        setData: setEditData, 
        put: putEdit, 
        processing: editProcessing, 
        errors: editErrors, 
        reset: resetEdit 
    } = useForm({
        name: '',
        address: '',
    });

    const openAddModal = () => {
        setIsAddModalOpen(true);
    };

    const closeAddModal = () => {
        setIsAddModalOpen(false);
        resetAdd();
    };

    const submitAdd = (e: React.SyntheticEvent) => {
        e.preventDefault();
        postAdd(route('admin.outlets.store'), {
            onSuccess: () => closeAddModal(),
        });
    };

    const openEditModal = (outlet: Outlet) => {
        setSelectedOutlet(outlet);
        setEditData({ name: outlet.name, address: outlet.address });
        setIsEditModalOpen(true);
    };

    const closeEditModal = () => {
        setIsEditModalOpen(false);
        setSelectedOutlet(null);
        resetEdit();
    };

    const submitEdit = (e: React.SyntheticEvent) => {
        e.preventDefault();
        if (!selectedOutlet) return;
        putEdit(route('admin.outlets.update', selectedOutlet.id), {
            onSuccess: () => closeEditModal(),
        });
    };

    const openDeleteModal = (outlet: Outlet) => {
        setSelectedOutlet(outlet);
        setIsDeleteModalOpen(true);
    };

    const closeDeleteModal = () => {
        setIsDeleteModalOpen(false);
        setSelectedOutlet(null);
    };

    const confirmDelete = () => {
        if (!selectedOutlet) return;
        router.delete(route('admin.outlets.destroy', selectedOutlet.id), {
            onSuccess: () => closeDeleteModal(),
        });
    };

    return (
        <AdminLayout
            header={
                <h1 className="text-3xl text-gray-800 font-koulen uppercase tracking-wide">
                    Manajemen Outlet
                </h1>
            }
        >
            <Head title="Manajemen Outlet" />

            <div className="bg-white shadow-md rounded-xl p-6">
                <div className="flex justify-between items-center mb-6">
                    <h2 className="text-xl font-bold text-gray-800">Daftar Outlet</h2>
                    <button
                        onClick={openAddModal}
                        className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition duration-200"
                    >
                        <Plus className="w-5 h-5" />
                        Tambah Outlet
                    </button>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-gray-100 text-gray-700">
                                <th className="p-4 rounded-tl-lg font-semibold">Nama Outlet</th>
                                <th className="p-4 font-semibold">Lokasi</th>
                                <th className="p-4 font-semibold">Nama Owner</th>
                                <th className="p-4 font-semibold">Email Owner</th>
                                <th className="p-4 rounded-tr-lg font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {outlets.length > 0 ? (
                                outlets.map((outlet) => (
                                    <tr key={outlet.id} className="border-b hover:bg-gray-50 transition border-gray-200">
                                        <td className="p-4 text-gray-800 font-medium">{outlet.name}</td>
                                        <td className="p-4 text-gray-600">{outlet.address}</td>
                                        <td className="p-4 text-gray-600">{outlet.owner?.name || '-'}</td>
                                        <td className="p-4 text-gray-600">{outlet.owner?.email || '-'}</td>
                                        <td className="p-4 flex gap-2 justify-center">
                                            <button
                                                onClick={() => openEditModal(outlet)}
                                                className="p-2 bg-yellow-100 text-yellow-600 hover:bg-yellow-200 rounded-lg transition"
                                                title="Edit"
                                            >
                                                <Edit className="w-4 h-4" />
                                            </button>
                                            <button
                                                onClick={() => openDeleteModal(outlet)}
                                                className="p-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg transition"
                                                title="Hapus"
                                            >
                                                <Trash className="w-4 h-4" />
                                            </button>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={5} className="p-8 text-center text-gray-500">
                                        Belum ada outlet yang ditambahkan.
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
                            <h3 className="font-bold text-lg">Tambah Outlet Baru</h3>
                            <button onClick={closeAddModal} className="text-gray-400 hover:text-gray-600">
                                <X className="w-5 h-5"/>
                            </button>
                        </div>
                        <form onSubmit={submitAdd} className="p-4">
                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Nama Outlet</label>
                                    <input 
                                        type="text" 
                                        className="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        value={addData.name}
                                        onChange={e => setAddData('name', e.target.value)}
                                        required 
                                    />
                                    {addErrors.name && <p className="text-red-500 text-xs mt-1">{addErrors.name}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                                    <textarea 
                                        className="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        value={addData.address}
                                        onChange={e => setAddData('address', e.target.value)}
                                        required 
                                        rows={2}
                                    />
                                    {addErrors.address && <p className="text-red-500 text-xs mt-1">{addErrors.address}</p>}
                                </div>
                                <hr />
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Nama Owner</label>
                                    <input 
                                        type="text" 
                                        className="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        value={addData.owner_name}
                                        onChange={e => setAddData('owner_name', e.target.value)}
                                        required 
                                    />
                                    {addErrors.owner_name && <p className="text-red-500 text-xs mt-1">{addErrors.owner_name}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Email Owner</label>
                                    <input 
                                        type="email" 
                                        className="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        value={addData.owner_email}
                                        onChange={e => setAddData('owner_email', e.target.value)}
                                        required 
                                    />
                                    {addErrors.owner_email && <p className="text-red-500 text-xs mt-1">{addErrors.owner_email}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Password Owner</label>
                                    <input 
                                        type="password" 
                                        className="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        value={addData.owner_password}
                                        onChange={e => setAddData('owner_password', e.target.value)}
                                        required 
                                        minLength={8}
                                    />
                                    {addErrors.owner_password && <p className="text-red-500 text-xs mt-1">{addErrors.owner_password}</p>}
                                </div>
                            </div>
                            <div className="mt-6 flex justify-end gap-2">
                                <button type="button" onClick={closeAddModal} className="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Batal</button>
                                <button type="submit" disabled={addProcessing} className="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg disabled:opacity-50">Simpan</button>
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
                            <h3 className="font-bold text-lg">Edit Outlet</h3>
                            <button onClick={closeEditModal} className="text-gray-400 hover:text-gray-600">
                                <X className="w-5 h-5"/>
                            </button>
                        </div>
                        <form onSubmit={submitEdit} className="p-4">
                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Nama Outlet</label>
                                    <input 
                                        type="text" 
                                        className="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        value={editData.name}
                                        onChange={e => setEditData('name', e.target.value)}
                                        required 
                                    />
                                    {editErrors.name && <p className="text-red-500 text-xs mt-1">{editErrors.name}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                                    <textarea 
                                        className="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        value={editData.address}
                                        onChange={e => setEditData('address', e.target.value)}
                                        required 
                                        rows={2}
                                    />
                                    {editErrors.address && <p className="text-red-500 text-xs mt-1">{editErrors.address}</p>}
                                </div>
                            </div>
                            <div className="mt-6 flex justify-end gap-2">
                                <button type="button" onClick={closeEditModal} className="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Batal</button>
                                <button type="submit" disabled={editProcessing} className="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg disabled:opacity-50">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* Modal Hapus */}
            {isDeleteModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
                        <div className="p-6 text-center">
                            <div className="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <Trash className="w-8 h-8"/>
                            </div>
                            <h3 className="font-bold text-xl mb-2">Hapus Outlet?</h3>
                            <p className="text-gray-500 mb-6">
                                Apakah Anda yakin ingin menghapus outlet <strong>{selectedOutlet?.name}</strong>? Tindakan ini tidak dapat dibatalkan.
                            </p>
                            <div className="flex justify-center gap-3">
                                <button onClick={closeDeleteModal} className="px-6 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition font-medium">Batal</button>
                                <button onClick={confirmDelete} className="px-6 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg transition font-medium">Ya, Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}