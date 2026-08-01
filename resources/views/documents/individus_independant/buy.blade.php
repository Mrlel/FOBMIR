@extends('layouts.individu')

@section('content')
<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --glass-bg: rgba(255, 255, 255, 0.7);
    }

    /* Style de la carte principale */
    .payment-card {
        border: none;
        border-radius: 15px;
        background: white;
        box-shadow: 0 10px 30px rgba(23, 30, 76, 0.1);
        overflow: hidden;
    }

    /* Header avec effet Glassmorphism */
    .payment-header {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(182, 140, 54, 0.2);
        padding: 2rem;
        text-align: center;
    }

    .icon-box {
        width: 60px;
        height: 60px;
        background: rgba(182, 140, 54, 0.1);
        color: var(--primary-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 15px;
        font-size: 1.8rem;
    }

    /* Inputs stylisés */
    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 0.2rem rgba(182, 140, 54, 0.15);
    }

    label {
        font-weight: 600;
        color: var(--secondary-blue);
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    /* Bouton premium */
    .btn-blue {
        background: var(--secondary-blue);
        color: white;
        border-radius: 10px;
        font-weight: 700;
        padding: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
        border: none;
    }

    .btn-blue:hover {
        background: #252f6b;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(23, 30, 76, 0.3);
        color: white;
    }

    .price-tag {
        color: var(--primary-gold);
        font-weight: 800;
        font-size: 1.8rem;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card payment-card">


                <div class="card-body p-4 p-md-5">        
                    <form action="{{ route('payment.initiate', $document->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label><i class="bi bi-person me-2"></i>Nom complet</label>
                            <input type="text" name="nom" class="form-control" placeholder="Ex: Jean Dupont" required>
                        </div>

                        <div class="mb-3">
                            <label><i class="bi bi-envelope me-2"></i>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="exemple@mail.com" required>
                        </div>

                        <div class="mb-4">
                            <label><i class="bi bi-phone me-2"></i>Téléphone (Orange / MTN / Moov)</label>
                            <input type="tel" name="telephone" class="form-control" placeholder="0707070707" required>
                        </div>

                        <button type="submit" class="btn btn-blue w-100 shadow-sm">
                            <i class="bi bi-credit-card-2-back me-2"></i> Payer maintenant
                        </button>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted tiny" style="font-size: 0.75rem;">
                                <i class="bi bi-lock-fill me-1"></i> Paiement sécurisé par cryptage SSL
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection