<!-- Modal -->
<div id="myModal" class="modal fade">
    <div class="modal-dialog" id="preview-modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <ul class="modal__button-list">
                        <li class="modal__button-item"><button class="btn btn__desktop active">Desktop</button></li>
                        <li class="modal__button-item"><button class="btn btn__tablet">Tablet</button></li>
                        <li class="modal__button-item"><button class="btn btn__mobile">Mobile</button></li>
                    </ul>
                </div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" >
                <iframe src="https://beta.kireibd.com/product/{{ $product->slug }}" title="{{ $product->name }}"></iframe>
            </div>
        </div>
    </div>
</div>

<style>
    .modal__button-list{
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 15px;
        list-style: none;
        margin-bottom: 0;
        padding-left: 0;
    }
    .modal__button-item .btn{
        border: 1px solid black;
    }
    .modal__button-item .btn.active{
        border: 1px solid black;
        background-color: black;
        color:white;
    }
    .modal-dialog{
        max-width: 1320px;
        transition: all .3s linear;
    }
    .modal-content .modal-body{
        min-height: 600px;
    }
    .modal-content .modal-body iframe{
        width: 100%;
        height: 100%;
    }
</style>