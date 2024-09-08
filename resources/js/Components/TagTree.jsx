import React, { useRef, useState, useEffect } from 'react';
import { Button } from "primereact/button";
import { Tree } from 'primereact/tree';
import { Inertia } from '@inertiajs/inertia'
import { Toolbar} from "primereact/toolbar";
import ModalText from "@/Components/Modals/Text"

export default function TagTree({ updateTag }) {
    const [tags, setTags] = useState([]);
    useEffect(() => {
        axios.get('/tags/tree',  {headers: {"x-response-format":'primereact'}} )
            .then(response => {
                setTags(response.data);
            })
    }, []);

    return (
        <>
            <Tree selectionMode="single" value={tags} className="w-full md:w-30rem"
                  onSelectionChange={(e) => {
                      updateTag(e.value)
                  }}/>
        </>
    );
}
