import { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { router } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

export default function Dashboard() {
    const [file, setFile] = useState<File | null>(null);
    const [isUploading, setIsUploading] = useState(false);

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const selected = e.target.files?.[0] || null;
        setFile(selected);
    };

    const handleUpload = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!file) return;

        setIsUploading(true);

        const formData = new FormData();
        formData.append('payment_file', file);

        try {
            await axios.post('api/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            });
            alert('Upload successful!');
        } catch (error) {
            console.log(error);
            alert('Upload failed!');
        } finally {
            setIsUploading(false);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="p-4">
                <form onSubmit={handleUpload} className="flex items-center gap-3">
                    {/* File input styled as button */}
                    <label className="inline-flex items-center px-3 py-2 rounded-lg bg-gray-200 text-gray-800 cursor-pointer hover:bg-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        Choose File
                        <input
                            type="file"
                            onChange={handleFileChange}
                            className="hidden"
                        />
                    </label>

                    {/* Show selected file name */}
                    {file && (
                        <span className="text-sm text-neutral-700 dark:text-neutral-300 truncate max-w-[200px]">
                            {file.name}
                        </span>
                    )}

                    {/* Upload button */}
                    <button
                        type="submit"
                        disabled={!file || isUploading}
                        className="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50"
                    >
                        {isUploading ? 'Uploading...' : 'Upload'}
                    </button>
                </form>
            </div>
        </AppLayout>
    );
}
