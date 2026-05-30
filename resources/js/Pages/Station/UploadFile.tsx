import React, { useRef, useState } from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
import { CheckCircle, XCircle } from 'lucide-react';
import { route } from 'ziggy-js';

interface UploadFileProps {
    stationId: number;
    stationName: string;
}

interface SharedProps {
    flash?: {
        success?: string;
    };
    errors?: Record<string, string>;
    [key: string]: any;
}

export default function UploadFile({ stationId, stationName }: UploadFileProps) {
    const { props } = usePage();
    const flash = (props as unknown as SharedProps).flash;
    const errors = props.errors || {};

    const { data, setData, post, processing, reset } = useForm({
        station_id: stationId,
        files: [] as File[],
        _method: 'POST',
    });

    const fileInputRef = useRef<HTMLInputElement>(null);
    const [dragging, setDragging] = useState(false);

    const handleFiles = (e: React.ChangeEvent<HTMLInputElement> | React.DragEvent<HTMLDivElement>) => {
        let selectedFiles: File[] = [];

        if ('dataTransfer' in e) {
            selectedFiles = Array.from(e.dataTransfer.files);
        } else if (e.target instanceof HTMLInputElement && e.target.files) {
            selectedFiles = Array.from(e.target.files);
        }

        if (selectedFiles.length > 0) {
            setData('files', selectedFiles);
            // In React Inertia, we need to defer the submit until state updates
            // but useForm's post will use the current state. 
            // Alternative: pass data directly, but setData is async.
            // Better to trigger it via a useEffect or by passing data straight to post()
        }
    };

    // React's setData is async, so we use useEffect to submit once files are set, 
    // OR we can post immediately using an updated object.
    React.useEffect(() => {
        if (data.files.length > 0) {
            post(route('upa.upload.store', stationId), {
                preserveScroll: true,
                onSuccess: () => {
                    reset('files');
                    if (fileInputRef.current) {
                        fileInputRef.current.value = '';
                    }
                },
            });
        }
    }, [data.files]);

    const triggerInput = () => {
        if (fileInputRef.current) {
            fileInputRef.current.click();
        }
    };

    const onDragOver = (e: React.DragEvent<HTMLDivElement>) => {
        e.preventDefault();
        setDragging(true);
    };

    const onDragLeave = (e: React.DragEvent<HTMLDivElement>) => {
        e.preventDefault();
        setDragging(false);
    };

    const onDrop = (e: React.DragEvent<HTMLDivElement>) => {
        e.preventDefault();
        setDragging(false);
        handleFiles(e);
    };

    return (
        <div className="h-screen flex flex-col items-center justify-center p-6 bg-[#FAFAFA] font-roboto">
            <Head title="Upload File" />

            {/* HEADER */}
            <div className="flex flex-col items-center mb-8">
                <div className="flex items-center gap-2 mb-2">
                    <img src="/images/logo.png" className="w-12 h-12" alt="Logo" />
                    <h1 className="text-4xl font-koulen text-gray-900 leading-none">PRINTATION</h1>
                </div>
                <h2 className="uppercase font-bold text-gray-400 font-roboto tracking-wider text-sm">
                    {stationName}
                </h2>
            </div>

            {/* UPLOAD AREA */}
            <div 
                onClick={triggerInput} 
                onDragOver={onDragOver} 
                onDragLeave={onDragLeave}
                onDrop={onDrop}
                className={`w-full max-w-sm aspect-square border-2 border-dashed rounded-[30px] flex flex-col items-center justify-center p-8 transition-all cursor-pointer relative overflow-hidden group ${
                    dragging ? 'border-brand-600 bg-blue-50' : 'border-[#6155F5] hover:bg-blue-50 bg-[#F7F8FF]'
                }`}
            >
                <input 
                    type="file" 
                    ref={fileInputRef} 
                    className="hidden" 
                    multiple 
                    onChange={handleFiles}
                    accept=".pdf,.jpg,.jpeg,.png,.docx" 
                />

                {/* Loading State */}
                {processing && (
                    <div className="absolute inset-0 bg-white/80 backdrop-blur-sm z-10 flex flex-col items-center justify-center">
                        <div className="w-16 h-16 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin mb-4">
                        </div>
                        <p className="font-bold text-indigo-600 animate-pulse">Mengunggah...</p>
                    </div>
                )}

                {/* Content */}
                <img src="/images/upload.png"
                    className="w-24 mb-6 opacity-80 group-hover:scale-110 transition-transform duration-300" alt="Upload" />

                <p className="text-xl font-bold text-gray-800 text-center mb-1">
                    Klik disini untuk <br/> mengunggah file
                </p>

                <span className="text-xs text-gray-400 text-center mt-2 leading-relaxed font-medium">
                    Format PDF, PNG, JPG, JPEG <br/>
                    Maks. 10MB
                </span>
            </div>

            {/* SUCCESS MESSAGE */}
            {flash?.success && (
                <div className="mt-6 flex flex-col items-center animate-in fade-in slide-in-from-bottom-4">
                    <div className="flex items-center gap-2 text-green-600 font-bold text-lg">
                        <CheckCircle className="w-6 h-6" />
                        <span>{flash.success}</span>
                    </div>
                    <p className="text-gray-500 text-sm mt-1 font-medium">Silakan cek di layar monitor station</p>
                </div>
            )}

            {/* ERROR MESSAGE */}
            {errors?.files && (
                <div className="mt-6 flex flex-col items-center animate-in fade-in slide-in-from-bottom-4">
                    <div className="flex items-center gap-2 text-red-600 font-bold text-lg text-center">
                        <XCircle className="w-6 h-6" />
                        <span>{errors.files}</span>
                    </div>
                    <p className="text-gray-500 text-sm mt-1 font-medium">Coba file lain</p>
                </div>
            )}

            {/* GENERIC ERROR */}
            {Object.keys(errors || {}).length > 0 && !errors?.files && (
                <div className="mt-6 flex flex-col items-center animate-in fade-in slide-in-from-bottom-4">
                    <div className="flex items-center gap-2 text-red-600 font-bold text-lg text-center">
                        <XCircle className="w-6 h-6" />
                        <span>Terjadi Kesalahan</span>
                    </div>
                    <ul className="text-gray-500 text-sm mt-1 font-medium list-disc">
                        {Object.keys(errors).map(key => (
                            <li key={key}>{errors[key]}</li>
                        ))}
                    </ul>
                </div>
            )}

        </div>
    );
}
