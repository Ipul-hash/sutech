@extends('layouts.app')
@section('title', 'Monitoring Jaringan - Helpdesk')

@section('content')
<div class="relative w-full h-[calc(100vh-80px)]">

    {{-- TOMBOL + ADD NODE --}}
    <button id="addNodeBtn"
        class="absolute top-4 left-4 z-50 bg-blue-600 text-white w-10 h-10 rounded-xl flex items-center justify-center text-2xl shadow-lg hover:bg-blue-700 transition">
        +
    </button>

    {{-- CANVAS GRID --}}
    <div id="canvasArea"
        class="w-full h-full relative"
        style="
        background-image:
            linear-gradient(to right, rgba(0,0,0,0.08) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(0,0,0,0.08) 1px, transparent 1px);
        background-size: 30px 30px;
        ">
    </div>

    {{-- MODAL PILIH NODE --}}
    <div id="nodeModal"
         class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

        <div class="bg-white p-6 rounded-xl w-64 shadow-xl">
            <h3 class="text-lg font-semibold mb-4">Pilih Node</h3>

            <div class="flex flex-col space-y-3">

                <button class="node-option flex items-center gap-3 p-2 rounded-lg hover:bg-gray-200"
                        data-type="router">
                    <i class="fas fa-router text-blue-600"></i> Router
                </button>

                <button class="node-option flex items-center gap-3 p-2 rounded-lg hover:bg-gray-200"
                        data-type="switch">
                    <i class="fas fa-network-wired text-green-600"></i> Switch
                </button>

                <button class="node-option flex items-center gap-3 p-2 rounded-lg hover:bg-gray-200"
                        data-type="server">
                    <i class="fas fa-server text-purple-600"></i> Server
                </button>

                <button class="node-option flex items-center gap-3 p-2 rounded-lg hover:bg-gray-200"
                        data-type="pc">
                    <i class="fas fa-desktop text-gray-800"></i> PC / Client
                </button>

            </div>

            <button id="closeModal"
                class="mt-5 w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600">
                Batal
            </button>
        </div>

    </div>

</div>


{{-- SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", () => {

    const addNodeBtn = document.getElementById('addNodeBtn');
    const nodeModal = document.getElementById('nodeModal');
    const closeModal = document.getElementById('closeModal');
    const canvas = document.getElementById('canvasArea');

    // === OPEN MODAL ===
    addNodeBtn.addEventListener('click', () => {
        nodeModal.classList.remove('hidden');
    });

    // === CLOSE MODAL ===
    closeModal.addEventListener('click', () => {
        nodeModal.classList.add('hidden');
    });

    // === WHEN USER SELECTS A NODE ===
    document.querySelectorAll('.node-option').forEach(btn => {
        btn.addEventListener('click', () => {
            const type = btn.getAttribute('data-type');
            addNodeToCanvas(type);
            nodeModal.classList.add('hidden');
        });
    });

    // === ADD NODE TO CANVAS ===
    function addNodeToCanvas(type) {

        const node = document.createElement('div');
        node.className = "node-item absolute cursor-pointer p-3 rounded-lg shadow-md bg-white border flex items-center gap-2";
        node.style.left = "100px";
        node.style.top = "100px";

        // Ikon sesuai jenis
        let icon = "";
        if (type === "router") icon = '<i class="fas fa-router text-blue-600"></i>';
        if (type === "switch") icon = '<i class="fas fa-network-wired text-green-600"></i>';
        if (type === "server") icon = '<i class="fas fa-server text-purple-600"></i>';
        if (type === "pc") icon = '<i class="fas fa-desktop text-gray-800"></i>';

        node.innerHTML = `${icon} <span class="text-sm font-semibold capitalize">${type}</span>`;

        canvas.appendChild(node);

        enableDrag(node);
    }

    // === DRAG & DROP NODE ===
    function enableDrag(elmnt) {
        let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

        elmnt.onmousedown = dragMouseDown;

        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();

            pos3 = e.clientX;
            pos4 = e.clientY;

            document.onmouseup = closeDragElement;
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();

            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;

            pos3 = e.clientX;
            pos4 = e.clientY;

            elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
            elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        }

        function closeDragElement() {
            document.onmouseup = null;
            document.onmousemove = null;
        }
    }

});
</script>

@endsection
