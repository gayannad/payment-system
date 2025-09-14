import { useState, useEffect } from 'react';
import axios from 'axios';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Invoices', href: '/invoices' },
];

interface Invoice {
    id: number;
    customer_name: string;
    invoice_number: string;
    created_at: string;
    amount: number;
    is_sent: boolean;
}

export default function Invoices() {
    const [invoices, setInvoices] = useState<Invoice[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchInvoices = async () => {
            try {
                const response = await axios.get('/api/invoices');

                const invoicesArray = response.data.data?.data || [];
                setInvoices(invoicesArray);
            } catch (err) {
                console.error('Error fetching invoices:', err);
                setInvoices([]);
            }
            setLoading(false);
        };

        fetchInvoices();
    }, []);


    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Invoices" />
            <div className="p-4">
                {loading ? (
                    <div className="text-center py-8">Loading...</div>
                ) : invoices.length === 0 ? (
                    <div className="text-center py-8">No invoices found.</div>
                ) : (
                    <table className="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <thead className="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th className="px-4 py-2 text-left">#</th>
                            <th className="px-4 py-2 text-left">Invoice Number</th>
                            <th className="px-4 py-2 text-left">Customer</th>
                            <th className="px-4 py-2 text-left">Amount(USD)</th>
                            <th className="px-4 py-2 text-left">Date</th>
                            <th className="px-4 py-2 text-left">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        {invoices.map((i) => (
                            <tr key={i.id} className="border-t">
                                <td className="px-4 py-2">{i.id}</td>
                                <td className="px-4 py-2">{i.invoice_number}</td>
                                <td className="px-4 py-2">{i.customer_name}</td>
                                <td className="px-4 py-2">{i.amount}</td>
                                <td className="px-4 py-2">{new Date(i.created_at).toLocaleDateString()}</td>
                                <td className="px-4 py-2">{i.is_sent ? 'Invoice Sent' : 'Invoice Pending'}</td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                )}
            </div>
        </AppLayout>
    );
}
