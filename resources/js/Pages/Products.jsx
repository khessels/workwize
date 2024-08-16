import AuthenticatedBackendLayout from '@/Layouts/AuthenticatedBackendLayout';
import { Head, useRemember} from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia'
import React, { useRef, useState, useEffect } from 'react';

import { Button } from "primereact/button";
import { DataTable } from 'primereact/datatable';
import { Column } from 'primereact/column';

import { ServiceProducts } from '@/Services/Products';
import Dropdown from "@/Components/Dropdown.jsx";
import {publish} from "@/Components/js/Events.js";
import ModalAddProduct from "@/Components/Modals/Product/AddProduct"
import { Toolbar } from 'primereact/toolbar';

import CategoryTree from  "@/Components/CategoryTree"
import { Toast } from 'primereact/toast';

export default function Products({ auth, categories }) {
    const [productNodes, setProductNodes] = useState([]);
    const [categoryKey, setCategoryKey] = useState(undefined);
    const [products, setProducts] = useState([]);
    const toast = useRef(null);

    const startTableContent = (
        <React.Fragment>
            <Button icon="pi pi-plus" className="mr-2" onClick={(event) => {
                event.preventDefault();
                publish('modal-product-add', "show")
            }}/>
            <p>Products</p>
            {/*<Button icon="pi pi-print" className="mr-2" />*/}
            {/*<Button icon="pi pi-upload" />*/}
        </React.Fragment>
    );
    const updateCategoryKey = (key) => {
        setCategoryKey(key)
    }
    useEffect(() => {
        if(categoryKey !== undefined) {
            ServiceProducts.getTreeNodes(categoryKey).then(data => {
                setProducts(data)
            });
        }
    }, []);

    return (
        <AuthenticatedBackendLayout
            auth={auth}
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Products</h2>}
        >

            <Head title="Products"/>
            <Toast ref={toast}/>

            <div className="w-full flex flex-col sm:flex-row flex-wrap sm:flex-nowrap py-4 flex-grow">
                <div className="w-fixed flex-shrink flex-grow-0 px-4">
                    <div className="sticky top-0 p-4 w-full h-full">
                        <CategoryTree categories={categories} updateCategoryKey={updateCategoryKey}/>
                        <p>Key: {categoryKey}</p>
                    </div>
                </div>
                <main role="main" className="w-full flex-grow pt-1 px-3">
                    <div className="card">
                        <Toolbar start={startTableContent}/>
                    </div>
                    <div className="card h-64 flex bg-blue-500 text-white">
                        {/*<TreeTable value={productNodes} tableStyle={{minWidth: '50rem'}} paginator rows={25}*/}
                        {/*           rowsPerPageOptions={[25, 50, 100]}>*/}
                        {/*    <Column field="label" header="Name" expander></Column>*/}
                        {/*    <Column field="icon" header="Icon"></Column>*/}
                        {/*    <Column field="key" header="Key"></Column>*/}
                        {/*</TreeTable>*/}

                        <DataTable value={productNodes} tableStyle={{minWidth: '50rem'}}>
                            <Column field="key" header="Key"></Column>
                            <Column field="label" header="Name"></Column>
                            <Column field="data" header="Description"></Column>
                            <Column field="quantity" header="Quantity"></Column>
                        </DataTable>

                    </div>
                </main>
                <div className="flex-grow-0 px-2">
                    <div className="flex sm:flex-col px-2">
                        Sidebar
                    </div>
                </div>
            </div>
            <ModalAddProduct />
        </AuthenticatedBackendLayout>
    );
}
