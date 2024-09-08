import AuthenticatedBackendLayout from '@/Layouts/AuthenticatedBackendLayout';
import {Head, useRemember} from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia'
import React, {useEffect, useState} from 'react';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { TabView, TabPanel } from 'primereact/tabview';



export default function Tags({ auth }) {
    const [tags, setTags] = useState([]);
    const [queuedTags, setQueuedTags] = useState([]);
    const [activeTags, setActiveTags] = useState([]);
    useEffect(() => {
        axios.get('/tags/tree?filter=all' )
            .then(response => {
                setTags(response.data);
            })
    }, []);
    useEffect(() => {
        axios.get('/tags/active' )
            .then(response => {
                setActiveTags(response.data);
            })
    }, []);
    useEffect(() => {
        axios.get('/tags/queued' )
            .then(response => {
                setQueuedTags(response.data);
            })
    }, []);

    return (
        <AuthenticatedBackendLayout
            auth={auth}
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Tags</h2>}
        >
            <ToastContainer/>
            <Head title="Tags"/>
            <div className="grid grid-cols-5 grid-rows-5 gap-4">
                <div>
                    <TabView>
                        <TabPanel header="Tags">
                            <table className="table flex-1 rounded-[10px] object-top object-cover dark:block">
                                <thead>
                                    <tr>
                                        <th>Tag Id</th>
                                        <th>Section</th>
                                        <th>Name</th>
                                        <th>Expires</th>
                                        <th>Visible</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {tags.map(function (item, index) {
                                        return item.tags.map(function(tag, tagIndex){
                                            let expiresAt = '';
                                            if( tag.expires_at !== null){
                                                expiresAt = tag.expires_at
                                            }
                                            return (
                                                <>
                                                    <tr key={tag.id}>
                                                        <td>{tag.id}</td>
                                                        <td>{item.name}</td>
                                                        <td>{tag.name}</td>
                                                        <td>{expiresAt}</td>
                                                        <td>{tag.visible}</td>
                                                        <td>
                                                            <button className="btn btn-primary btn-sm" onClick={() => {
                                                                // axios.delete('/cart/item/' + item.id);
                                                                // notify_removed()
                                                                // Inertia.reload();
                                                            }}>Remove
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </>
                                            )
                                        });
                                    })}
                                </tbody>
                            </table>
                        </TabPanel>
                        <TabPanel header="Active tags">
                            <table className="table flex-1 rounded-[10px] object-top object-cover dark:block">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Section</th>
                                    <th>Tag</th>
                                    <th>Expires</th>
                                    <th>Visible</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                {activeTags.map(function (item, index) {
                                    let price = '-'
                                    if( item.product.prices.length > 0 ){
                                        price = item.product.prices[0].price;
                                    }
                                    return (
                                        <>
                                            <tr key={item.id}>
                                                <td>{item.product.name}</td>
                                                <td>{price}</td>
                                                <td>{item.tag.topic.name}</td>
                                                <td>{item.tag.name}</td>
                                                <td>{item.tag.expires_at}</td>
                                                <td>{item.tag.visible}</td>
                                                <td>
                                                    <button className="btn btn-primary btn-sm" onClick={() => {
                                                        // axios.delete('/cart/item/' + item.id);
                                                        // notify_removed()
                                                        // Inertia.reload();
                                                    }}>Remove
                                                    </button>
                                                </td>
                                            </tr>
                                        </>
                                    )
                                })}
                                </tbody>
                            </table>
                        </TabPanel>
                        <TabPanel header="Queued Tags">
                            <table className="table flex-1 rounded-[10px] object-top object-cover dark:block">
                                <thead>
                                <tr>
                                    <th>Section</th>
                                    <th>Tag</th>
                                    <th>Enables</th>
                                    <th>Expires</th>
                                    <th>Visible</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                {queuedTags.map(function (item, index) {
                                    return (
                                        <>
                                            <tr key={item.id}>
                                                <td>{item.topic.name}</td>
                                                <td>{item.name}</td>
                                                <td>{item.enables_at}</td>
                                                <td>{item.expires_at}</td>
                                                <td>{item.visible}</td>
                                                <td>
                                                    <button className="btn btn-primary btn-sm" onClick={() => {
                                                        // axios.delete('/cart/item/' + item.id);
                                                        // notify_removed()
                                                        // Inertia.reload();
                                                    }}>Remove
                                                    </button>
                                                </td>
                                            </tr>
                                        </>
                                    )
                                })}
                                </tbody>
                            </table>
                        </TabPanel>
                    </TabView>
                </div>
                <div className="col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg py-6">

                </div>
                <div className="col-start-5">
                    &nbsp;
                </div>
                <div className="row-start-2">&nbsp;</div>
                <div className="col-span-3 row-start-2 bg-white overflow-hidden shadow-sm sm:rounded-lg py-6">

                </div>
                <div className="col-start-5 row-start-2">&nbsp;</div>
                <div className="col-span-5 row-start-3">&nbsp;</div>
            </div>
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">


                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedBackendLayout>
    );
}
