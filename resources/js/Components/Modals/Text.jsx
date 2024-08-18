import { useState } from 'react';
import { publish, subscribe} from "@/Components/js/Events.js";
import { Button } from "primereact/button";
import { Dialog } from "primereact/dialog";
import { InputText } from 'primereact/inputtext';
import { FloatLabel} from "primereact/floatlabel";

export default function Quantity( props) {

    const [isShow, setShow] = useState(false);
    let value = props.initialValue;

    subscribe("modals", (data) =>{
        if(data.detail === 'hide'){
            setShow(false)
        }
    });
    subscribe("modal-text", (data) =>{
        if(data.detail === 'show'){
            setShow(true)
        }else if(data.detail === 'hide'){
            setShow(false)
        }
    });
    return (
        <div className="card flex justify-content-center">
            <Dialog header={props.dlgHeader} visible={isShow} style={{width: '30vw'}} onHide={() => {
                if (!isShow) return;
                setShow(false);
            }}
            content={({ hide }) => (
                <div className="flex flex-column px-8 py-5 gap-4" style={{
                    borderRadius: '12px',
                    backgroundImage: 'radial-gradient(circle at left top, var(--yellow-50), var(--yellow-100))' }}>
                    <InputText
                        id='numberText'
                        value={value}
                        onValueChange={(e) => {
                            value = e.value
                        }}/>
                    <Button onClick={(event) => {
                        event.preventDefault();
                        publish(props.cbEventName, value)
                        setShow(false)  }}>
                        Update
                    </Button>
                </div>
            )} />
        </div>
    );
}
