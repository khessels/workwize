import GuestLayout from '@/Layouts/GuestLayout';
import { Head, useRemember} from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia'
import React, { useRef, useState, useEffect } from 'react';
import {MegaMenu} from "primereact/megamenu";
import SetLayout from "@/Layouts/SetLayout"

export default function Product({ auth, product, categories }) {
    let Layout = SetLayout(auth.layout);
    return (
        <Layout
            auth={auth}
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Product</h2>}
        >
            <Head title="Product"/>
            <div className="w-full flex flex-col sm:flex-row flex-wrap sm:flex-nowrap py-4 flex-grow">
                <div className="w-fixed flex-shrink flex-grow-0 px-4">
                    <div className="sticky top-0 p-4 w-full h-full">
                        <MegaMenu model={categories[0].items} orientation="vertical" breakpoint="960px" />
                    </div>
                </div>
                <main role="main" className="w-full flex-grow pt-1 px-3">

                </main>
                <div className="flex-grow-0 px-2">
                    <div className="flex sm:flex-col px-2">
                        Sidebar
                    </div>
                </div>
            </div>
        </Layout>
    );
}
