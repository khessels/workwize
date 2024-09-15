import AuthenticatedBackendLayout from '@/Layouts/AuthenticatedBackendLayout';
import {Head, Link} from '@inertiajs/react';
import SetLayout from "@/Layouts/SetLayout"

export default function BackendDashboard({ auth }) {
    let Layout = SetLayout(auth.layout);
    return (
        <Layout
            auth={auth}
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">You're logged in! </div>
                        <Link href={route('categories.test')}>
                            Test Category
                        </Link>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
