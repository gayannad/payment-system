import { dashboard, login, register } from '@/routes';
import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="Welcome" />
            <div className="flex min-h-screen flex-col items-center justify-center bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 p-6">
                <header className="absolute top-6 right-6">
                    <nav className="flex items-center gap-4">
                        {auth.user ? (
                            <Link
                                href={dashboard()}
                                className="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                            >
                                Dashboard
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={login()}
                                    className="rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-200 dark:hover:text-indigo-400"
                                >
                                    Log in
                                </Link>
                                <Link
                                    href={register()}
                                    className="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                                >
                                    Register
                                </Link>
                            </>
                        )}
                    </nav>
                </header>

                <main className="text-center">
                    <h1 className="text-3xl font-bold mb-4">Welcome to Payment System</h1>

                    {!auth.user && (
                        <div className="flex justify-center gap-4">
                            <Link
                                href={register()}
                                className="rounded-md bg-indigo-600 px-5 py-2 text-white font-medium hover:bg-indigo-700"
                            >
                                Register
                            </Link>
                            <Link
                                href={login()}
                                className="rounded-md border border-gray-300 px-5 py-2 font-medium text-gray-700 hover:border-indigo-400 hover:text-indigo-600 dark:border-gray-600 dark:text-gray-200 dark:hover:border-indigo-400"
                            >
                                Log in
                            </Link>
                        </div>
                    )}
                </main>
            </div>
        </>
    );
}
