import React, { useRef, useState, useEffect } from 'react';
import { Toast } from 'primereact/toast';
import { Button } from "primereact/button";
import { Tree } from 'primereact/tree';
import {publish} from "@/Components/js/Events.js";
import {Toolbar} from "primereact/toolbar";
import ModalAddCategory from "@/Components/Modals/Category/AddCategory.jsx";
import { ServiceCategories } from '@/Services/Categories';

export default function CategoryTree({ categories, categoryKey, setCategoryKey }) {
    debugger;
    const toast = useRef(null);

    const startCategoryContent = (
        <React.Fragment>
            <Button icon="pi pi-plus" className="mr-2" onClick={(event) => {
                event.preventDefault();
                publish('modal-category-add', "show")
            }}/>
            {/*<Button icon="pi pi-print" className="mr-2" />*/}
            {/*<Button icon="pi pi-upload" />*/}
        </React.Fragment>
    );

    return (
        <>
            <div className="card">
                <Toolbar start={startCategoryContent}/>
            </div>

            <Tree selectionMode="single" value={categories} className="w-full md:w-30rem"
                  onSelectionChange={(e) => {
                      setCategory(e.value)
                  }}/>
            <ModalAddCategory/>
        </>
    );
}
