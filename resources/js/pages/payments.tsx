import { useState, useEffect } from 'react';
import axios from 'axios';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Payments', href: '/payments' },
];

interface Payment {
    id: number;
    customer_name: string;
    currency: string;
    payment_date: string;
    amount: number;
    usd_amount: number;
    is_processed: boolean;
}

export default function Payments() {
    const [payments, setPayments] = useState<Payment[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchPayments = async () => {
            try {
                const response = await axios.get('/api/payments');
                console.log('API Response:', response.data);

                const paymentsArray = response.data.data?.data || [];
                setPayments(paymentsArray);
            } catch (err) {
                console.error('Error fetching payments:', err);
                setPayments([]);
            }
            setLoading(false);
        };

        fetchPayments();
    }, []);


    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Payments" />
            <div className="p-4">
                {loading ? (
                    <div className="text-center py-8">Loading...</div>
                ) : payments.length === 0 ? (
                    <div className="text-center py-8">No payments found.</div>
                ) : (
                    <table className="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <thead className="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th className="px-4 py-2 text-left">#</th>
                            <th className="px-4 py-2 text-left">Customer</th>
                            <th className="px-4 py-2 text-left">Amount</th>
                            <th className="px-4 py-2 text-left">USD Amount</th>
                            <th className="px-4 py-2 text-left">Date</th>
                            <th className="px-4 py-2 text-left">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        {payments.map((p) => (
                            <tr key={p.id} className="border-t">
                                <td className="px-4 py-2">{p.id}</td>
                                <td className="px-4 py-2">{p.customer_name}</td>
                                <td className="px-4 py-2">{p.currency} {p.amount}</td>
                                <td className="px-4 py-2">USD {p.usd_amount}</td>
                                <td className="px-4 py-2">{new Date(p.payment_date).toLocaleDateString()}</td>
                                <td className="px-4 py-2">{p.is_processed ? 'Processed' : 'Pending'}</td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                )}
            </div>
        </AppLayout>
    );
}
