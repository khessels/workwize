import React, { useRef, useState, useEffect } from 'react';
import { Toast } from 'primereact/toast';
import { Button } from "primereact/button";
import { Tree } from 'primereact/tree';
import {publish, subscribe} from "@/Components/js/Events.js";
import {Toolbar} from "primereact/toolbar";
import ModalAddCategory from "@/Components/Modals/Category/AddCategory.jsx";
import { ServiceCategories } from '@/Services/Categories';
import ModalText from "@/Components/Modals/Text"
import ModalQuantity from "@/Components/Modals/Quantity.jsx";
import {Inertia} from "@inertiajs/inertia";
export default function CategoryTree({ categories, updateCategoryKey }) {

    const toast = useRef(null);
    const dlgHeader = "New Category name";
    let cbTextEventName = "event-category-name";
    subscribe(cbTextEventName, (data) =>{
        axios.post('/cart/item', state)
            .then(res => {
                Inertia.reload()
            })
    });
    const startCategoryContent = (
        <React.Fragment>
            <Button icon="pi pi-plus" className="mr-2" onClick={(event) => {
                // event.preventDefault();
                publish('modal-text', "show")
            }}/>
            <p>Categories</p>
            {/*<Button icon="pi pi-print" className="mr-2" />*/}
            {/*<Button icon="pi pi-upload" />*/}
        </React.Fragment>
    );

    return (
        <>
            <div className="card">
                <Toolbar start={startCategoryContent}/>
            </div>

            <Tree selectionMode="single" value={categories.root[0].children} className="w-full md:w-30rem"
                  onSelectionChange={(e) => {
                      updateCategoryKey(e.value)
                  }}/>
            <ModalText
                dlgHeader={dlgHeader}
                closeOnSubmit={true}
                cbEventName={cbTextEventName}
            />
        </>
    );
}
