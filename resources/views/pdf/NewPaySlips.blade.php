<div class="container">
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="invoice-container">
                        <div class="invoice-header">
                            <!-- Row start -->
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6" style="text-align: center;">
                                    <a href="#" class="invoice-logo">
                                        <img src="https://centre.academy-tutoriels.com/pluginfile.php/1/theme_moove/logo/1692166718/logo-font-transparant-les-tutoriels.png"
                                            alt="">
                                    </a>
                                </div>
                            </div>
                            <!-- Row end -->
                            <!-- Row start -->
                            <div class="row gutters">
                                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                                    <div class="invoice-details" style="text-align: left;">
                                        <address style="display: inline-block;">
                                            <h4>Informations générales</h4>
                                            <span>Nom et Prénoms : {{ $user->name }}</span>
                                            <br>
                                            <span>Email : {{ $user->email }}</span>
                                            <br>
                                            <span>Téléphone : {{ $user->phone }}</span>
                                            <span>Poste : {{ $user->poste }}</span>
                                        </address>
                                        <img src="{{ public_path('img/paye.png') }}" width="100px"
                                            alt="Description de l'image" style="display: inline-block;">
                                    </div>

                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                                    <div class="invoice-details">
                                        <div class="invoice-num">
                                            <div>Fiche de paie N°: {{ $paySlip->code }}</div>
                                            <div>Mois de : {{ $paySlip->month }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Row end -->
                        </div>
                        <div class="invoice-body">
                            <!-- Row start -->
                            <div class="row gutters">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table custom-table m-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col" colspan="4">Montant total</th>
                                                    <th scope="col">Etat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="4">
                                                        {{ $paySlip->amount }}
                                                    </td>
                                                    <td>
                                                        Payé
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <h5 class="text-success"><strong>Grand Total</strong></h5>
                                                    </td>
                                                    <td>
                                                        <h5 class="text-success"><strong>{{ $paySlip->amount }} Francs
                                                                CFA</strong></h5>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Row end -->
                        </div>


                        <div class="invoice-footer">
                            Propulsé par Les Tutoriels academy ({{ $paySlip->created_at }})
                        </div>
                        <div class="invoice-footer">
                            <img src="{{ public_path('img/cachet.png') }}" width="150px" alt="Description de l'image"
                                style="float: right;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    td {
        padding: 20px;
    }

    body {
        margin-top: 20px;
        color: #2e323c;
        position: relative;
        height: 100%;
    }

    .invoice-container {
        padding: 1rem;
    }

    .invoice-container .invoice-header .invoice-logo {
        margin: 0.8rem 0 0 0;
        display: inline-block;
        font-size: 1.6rem;
        font-weight: 700;
        color: #2e323c;
        text-align: center;
    }

    .invoice-container .invoice-header .invoice-logo img {
        max-width: 130px;
    }

    .invoice-container .invoice-header address {
        font-size: 0.8rem;
        color: #000;
        margin: 0;
    }

    .invoice-container .invoice-details {
        margin: 1rem 0 0 0;
        padding: 0.8rem;
        line-height: 180%;
        background: #f5f6fa;
    }

    .invoice-container .invoice-details .invoice-num {
        text-align: right;
        font-size: 0.8rem;
    }

    .invoice-container .invoice-body {
        padding: 1rem 0 0 0;
    }

    .invoice-container .invoice-footer {
        text-align: center;
        font-size: 0.7rem;
        margin: 5px 0 0 0;
    }



    .invoice-status h5.status-title {
        margin: 0 0 0.8rem 0;
        color: #9fa8b9;
    }

    .invoice-status p.status-type {
        margin: 0.5rem 0 0 0;
        padding: 0;
        line-height: 150%;
    }

    .invoice-status i {
        font-size: 1.5rem;
        margin: 0 0 1rem 0;
        display: inline-block;
        padding: 1rem;
        background: #f5f6fa;
        -webkit-border-radius: 50px;
        -moz-border-radius: 50px;
        border-radius: 50px;
    }

    .invoice-status .badge {
        text-transform: uppercase;
    }

    @media (max-width: 767px) {
        .invoice-container {
            padding: 1rem;
        }
    }


    .custom-table {
        border: 1px solid #e0e3ec;
        border-collapse: collapse;
    }

    .custom-table thead {
        background: #007ae1;
    }

    .custom-table thead th {
        border: 1px solid #e0e3ec;
        border-collapse: collapse;
        color: #ffffff;
    }

    .custom-table>tbody tr:hover {
        background: #fafafa;
    }

    .custom-table>tbody tr:nth-of-type(even) {
        background-color: #ffffff;
    }

    .custom-table>tbody td {
        border: 1px solid #e6e9f0;
    }


    .card {
        background: #ffffff;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        border: 0;
        margin-bottom: 1rem;
    }

    .text-success {
        color: #00bb42 !important;
    }

    .text-muted {
        color: #9fa8b9 !important;
    }

    .custom-actions-btns {
        margin: auto;
        display: flex;
        justify-content: flex-end;
    }

    .custom-actions-btns .btn {
        margin: .3rem 0 .3rem .3rem;
    }

    table {
        width: 100%;
    }

    td {
        align-items: center;
    }
</style>
