import { Head, usePage } from '@inertiajs/react';
import { t} from "@/Components/js/t";
import ProductRow from "@/Components/ProductRow";
import React from "react";
import { MegaMenu } from 'primereact/megamenu';
import TagTree from  "@/Components/TagTree"
import SetLayout from "@/Layouts/SetLayout"
import { ToastContainer, toast } from 'react-toastify';

export default function Welcome({ auth, productsByTag, categories }) {
    let Layout = SetLayout(auth.layout);

    const { localeData } = usePage().props;
    const { flash } = usePage().props
    debugger;
    const d = localeData.data
    const items = categories[0].items
    const updateTag = (tag) => {
        console.log(tag)
        window.location = '/products?tags=' + tag;
    }

    return (

        <Layout user={auth.user}>
            <ToastContainer/>
            {flash && (
                toast("Test")
            )}
            <Head title={t(d, "title" )}/>
            <div className="w-full flex flex-col sm:flex-row flex-wrap sm:flex-nowrap py-4 flex-grow">
                <div className="w-fixed flex-shrink flex-grow-0 px-4">
                    <div className="sticky top-0 p-4 w-full h-full">
                        <MegaMenu model={items} orientation="vertical" breakpoint="960px" /><br />
                        <TagTree updateTag={updateTag}/>
                    </div>
                </div>
                <main role="main" className="flex sm:flex-col md:px-6">
                    {
                        productsByTag.map( ( taggedProductRow, i) =>
                            <div className="flex-row " key={i}>
                                <ProductRow key={i} taggedProductRow={ taggedProductRow}/>
                            </div>
                        )
                    }
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

