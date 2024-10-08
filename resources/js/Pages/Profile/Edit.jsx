import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DeleteUserForm from './Partials/DeleteUserForm';
import UpdatePasswordForm from './Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm';
import { Head } from '@inertiajs/react';

export default function Edit({ auth, mustVerifyEmail, status }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>}
        >
            <Head title="Profile"/>


            <div className="grid grid-cols-5 grid-rows-5 gap-4">
                <div className="col-span-3 col-start-2 row-start-1">
                    <div className="py-12">
                        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                            <div className="p-4 sm:p-8 sm:rounded-lg">
                                <UpdateProfileInformationForm
                                    mustVerifyEmail={mustVerifyEmail}
                                    status={status}
                                    className="max-w-xl"
                                />
                            </div>

                            <div className="p-4 sm:p-8 sm:rounded-lg">
                                <UpdatePasswordForm className="max-w-xl"/>
                            </div>

                            <div className="p-4 sm:p-8 sm:rounded-lg">
                                <DeleteUserForm className="max-w-xl"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-start-1 row-start-1">&nbsp;</div>
                <div className="col-start-5">&nbsp;</div>
            </div>

        </AuthenticatedLayout>
    );
}
