import { useState } from 'react';
import { publish, subscribe} from "@/Components/js/Events.js";
import { Button } from "primereact/button";
import { Dialog } from "primereact/dialog";
import { InputNumber } from 'primereact/inputnumber';
import { FloatLabel} from "primereact/floatlabel";

export default function Quantity( init) {

    const [isShow, setShow] = useState(false);
    let quantity = init.initialValue;

    subscribe("modals", (data) =>{
        if(data.detail === 'hide'){
            setShow(false)
        }
    });
    subscribe("modal-quantity", (data) =>{
        if(data.detail === 'show'){
            setShow(true)
        }else if(data.detail === 'hide'){
            setShow(false)
        }
    });

    // const footerContent = (
    //     <div>
    //         <Button label="Close" icon="pi pi-times" onClick={() => {
    //             setShow(false)
    //         }} className="p-button-text" />
    //     </div>
    // );
    return (
        <div className="card flex justify-content-center">
            <Dialog header={init.dlgHeader} visible={isShow} style={{width: '30vw'}} onHide={() => {
                if (!isShow) return;
                setShow(false);
            }}
            content={({ hide }) => (
                <div className="flex flex-column px-8 py-5 gap-4" style={{ borderRadius: '12px', backgroundImage: 'radial-gradient(circle at left top, var(--yellow-50), var(--yellow-100))' }}>
                    <FloatLabel>
                        <label htmlFor="numberQuantity">Quantity:</label>
                        <InputNumber
                            id='numberQuantity'
                            value={quantity} min={init.minValue} max={init.maxValue}
                            onValueChange={(e) => {
                                quantity = e.value
                            }}/>
                        <Button onClick={(event) => {
                            event.preventDefault();
                            publish(init.cbEventName, quantity)
                            setShow(false)  }}>

                            Update
                        </Button>

                    </FloatLabel>
                </div>
            )} />



        </div>
    );
}
