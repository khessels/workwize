import AuthenticatedBackendLayout from '@/Layouts/AuthenticatedBackendLayout';
import {Head, useRemember} from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia'
import { useRef, useState, useEffect } from 'react';
import { Toast } from 'primereact/toast';
import { Button } from "primereact/button";
import { TreeTable } from 'primereact/treetable';
import { Column } from 'primereact/column';
import { NodeCategories } from '@/Services/NodeCategories.jsx';
import Dropdown from "@/Components/Dropdown.jsx";
import {publish} from "@/Components/js/Events.js";
import ModalAddCategory from "@/Components/Modals/Category/AddCategory.jsx"

export default function Categories({ auth, categories }) {

    const toast = useRef(null);
    const showToast = (detail, title = 'Categories', severity= 'info') => {
        toast.current.show({ severity: severity, summary: title, detail: detail });
    };
    const [nodes, setNodes] = useState([]);
    const columns = [
        { field: 'name', header: 'Name', expander: true },
        { field: 'size', header: 'Type' },
        { field: 'type', header: 'Size' }
    ];
    useEffect(() => {
        NodeCategories.getTreeTableNodes().then((data) => setNodes(data));
    }, []);
    return (
        <AuthenticatedBackendLayout
            auth={auth}
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Categories</h2>}
        >

            <Head title="Categories"/>
            <Toast ref={toast}/>

            <div className="w-full flex flex-col sm:flex-row flex-wrap sm:flex-nowrap py-4 flex-grow">
                <div className="w-fixed flex-shrink flex-grow-0 px-4">
                    <div className="sticky top-0 p-4 w-full h-full">
                        <Button onClick={(event) => {
                            event.preventDefault();
                            publish('modal-category-add', "show")
                        }}>Add Category</Button>
                    </div>
                </div>
                <main role="main" className="w-full flex-grow pt-1 px-3">
                    <div className="h-64 flex bg-blue-500 text-white">
                        <TreeTable value={nodes} tableStyle={{minWidth: '50rem'}}>
                            {columns.map((col, i) => (
                                <Column key={col.field} field={col.field} header={col.header} expander={col.expander}/>
                            ))}
                        </TreeTable>
                    </div>
                </main>
                <div className="flex-grow-0 px-2">
                    <div className="flex sm:flex-col px-2">
                        Sidebar
                    </div>
                </div>
            </div>

            <ModalAddCategory />
        </AuthenticatedBackendLayout>
    );
}
