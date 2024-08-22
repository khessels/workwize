
import AuthenticatedBackendLayout from '@/Layouts/AuthenticatedBackendLayout';
import { Head, useRemember} from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia'
import React, { useRef, useState, useEffect } from 'react';

import { Button } from "primereact/button";
import { DataTable } from 'primereact/datatable';
import { Column } from 'primereact/column';

import {publish} from "@/Components/js/Events.js";
import ModalAddProduct from "@/Components/Modals/Product/AddProduct"
import { Toolbar } from 'primereact/toolbar';

import CategoryTree from  "@/Components/CategoryTree"
import { Toast } from 'primereact/toast';

export default function Products({ auth, categories, categoryId, categoryParentId }) {
    // const [categoryKey, setCategoryKey] = useState(undefined);
    const [products, setProducts] = useState([]);
    const toast = useRef(null);

    const startTableContent = (
        <React.Fragment>
            <Button icon="pi pi-plus" className="mr-2" onClick={(event) => {
                event.preventDefault();
                publish('modal-product-add', "show")
            }}/>
            <p>Products</p>
        </React.Fragment>
    );

    const updateCategoryKey = (key) => {
        if(typeof key !== 'undefined') {
            axios.get('/products/category/key/' + key)
                .then(response => {
                    for(let x = 0; x < response.data.length; x++){
                        response.data[x].price = response.data[x].prices[0].price
                    }
                    setProducts(response.data);
                })
        }
    }
    useEffect(() => {
        if( categoryId !== null){
            let key = categoryId + '-' + categoryParentId;
            updateCategoryKey( key)
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
                    </div>
                </div>
                <main role="main" className="w-full flex-grow pt-1 px-3">
                    <div className="card">
                        <Toolbar start={startTableContent}/>
                    </div>
                    <div className="card h-64 flex">
                        <DataTable selectionMode="simple"  sortMode="multiple" paginator
                                   rows={25} rowsPerPageOptions={[25, 50, 100, 250]} stripedRows  size={'small'}
                                   value={products} tableStyle={{minWidth: '50rem'}} onRowSelect={(event) => {
                            Inertia.visit('/product/' + event.data.id);
                        }}>
                            <Column sortable    field="id"          header="Id"     />
                            <Column sortable field="name" header="name" body={ rowData => {
                                return(
                                    <a href={`/product/${rowData.id}`} >
                                        {rowData.name}
                                    </a>
                                )
                            }}  />
                            <Column sortable    field="price"       header="Price"  />
                            <Column sortable    field="stock"       header="Stock"  />
                            <Column sortable    field="tag_labels"  header="Tags"   />
                            <Column             field="action"      header="Action" body={ rowData => {
                                return(
                                    <Button size={'small'} onClick={() => {
                                        axios.post('/cart/item', {quantity:1, id:rowData.id})
                                            .then(res => {
                                                Inertia.reload()
                                            })
                                    }}>Add to Cart</Button>
                                )
                            }}/>
                        </DataTable>
                    </div>
                </main>
                <div className="flex-grow-0 px-2">
                    <div className="flex sm:flex-col px-2">
                        Sidebar
                    </div>
                </div>
            </div>
            <ModalAddProduct categories={categories}/>
        </AuthenticatedBackendLayout>
    );
}
