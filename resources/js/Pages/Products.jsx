
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
import TagTree from  "@/Components/TagTree"

import SetLayout from "@/Layouts/SetLayout"
import {ToastContainer, toast} from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
export default function Products({ auth, categoryId, categoryParentId }) {
    //console.log(auth)
    let Layout = SetLayout(auth.layout);
    const [products, setProducts] = useState([]);
    const [tag, setTag] = useState([]);


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
            axios.get('/products?categories=' + key)
                .then(response => {
                    for(let x = 0; x < response.data.length; x++){
                        response.data[x].price = response.data[x].prices[0]['price']
                    }
                    setProducts(response.data);
                })
        }
    }
    const updateTag = (tag) => {
        axios.get('/products?tags=' + tag)
            .then(response => {
                for(let x = 0; x < response.data.length; x++){
                    response.data[x].price = response.data[x].prices[0].price
                }
                setProducts(response.data);
            })
    }

    useEffect(() => {
        if( categoryId !== null){
            let key = categoryId + '-' + categoryParentId;
            updateCategoryKey( key)
        }
    }, []);
    return (
        <Layout
            auth={auth}
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Products</h2>}
        >
            <ToastContainer/>
            <Head title="Products"/>

            <div className="w-full flex flex-col sm:flex-row flex-wrap sm:flex-nowrap py-4 flex-grow">
                <div className="w-fixed flex-shrink flex-grow-0 px-4">
                    <div className="sticky top-0 p-4 w-full h-full">
                        <CategoryTree  updateCategoryKey={updateCategoryKey}/><br />
                        <TagTree updateTag={updateTag}/>
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
                                {
                                    if ( ! auth.isPublic ) {
                                        return(
                                            <Button size={'small'} onClick={() => {
                                                toast('Added')
                                                axios.post('/cart/item', {quantity:1, id:rowData.id})
                                                    .then(res => {
                                                        Inertia.reload()
                                                    })
                                            }}>Add to Cart</Button>
                                        )
                                    }else {
                                        return(
                                            <span>Login</span>
                                        )
                                    }
                                }
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
            <ModalAddProduct />
        </Layout>
    );
}
