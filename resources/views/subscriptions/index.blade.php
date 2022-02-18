<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('subscription.store') }}" id="form" method="post">
                        @csrf
                        <input type="text" name="card-hold-name" id="card-hold-name" placeholder="Nome no cartão"
                            class="shadow appearance-none border rounded w-full py-2 px-3 mb-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <div id="card-element" class="mt-4 mb-4"></div>
                        <div class="flex items-center justify-between mt-6">
                            <button type="submit" data-secret="{{ $intent->client_secret }}" id="card-button"
                                class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const stripe = Stripe("{{ config('cashier.key') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    //subscription payment
    const form = document.getElementById('form')
    const cardHoldName = document.getElementById('card-hold-name')
    const cardButton = document.getElementById('card-button')
    const clientSecret = cardButton.dataset.secret

    form.addEventListener('submit', async (e) => {
        e.preventDefault()
        const {
            setupIntent,
            error
        } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: cardHoldName.value
                    }
                }
            }

        )
        if (error) {
            console.log(error)
            return;
        }

        let token = document.createElement('input')
        token.setAttribute('type', 'text')
        token.setAttribute('name', 'token')
        token.setAttribute('value', setupIntent.payment_method)
        form.appendChild(token)

        form.submit()
    })
</script>
