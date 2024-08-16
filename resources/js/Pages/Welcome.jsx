import { Head, usePage } from '@inertiajs/react';
import GuestLayout from "@/Layouts/GuestLayout";
import {Button} from "primereact/button";
import {TreeTable} from "primereact/treetable";
import {Column} from "primereact/column";
import { t} from "@/Components/js/t";

export default function Welcome({ auth, laravelVersion, phpVersion, products, categories, cartsHistoryCount, salesCount, cartItemsCount  }) {
    const { localeData } = usePage().props;
    const d = localeData.data
    return (
        <GuestLayout>
            <Head title={t(d, "title" )}/>
            <div className="w-full flex flex-col sm:flex-row flex-wrap sm:flex-nowrap py-4 flex-grow">
                <div className="w-fixed flex-shrink flex-grow-0 px-4">
                    <div className="sticky top-0 p-4 w-full h-full">
                        side
                    </div>
                </div>
                <main role="main" className="w-full flex-grow pt-1 px-3">
                    <div className="h-64 flex ">
                        eee
                    </div>
                </main>
                <div className="flex-grow-0 px-2">
                    <div className="flex sm:flex-col px-2">
                        Sidebar
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}

