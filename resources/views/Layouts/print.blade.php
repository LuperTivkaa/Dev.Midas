<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?famaily=Open+Sans:300,400,600">
    <link rel="stylesheet" href="{{asset('css/print.css')}}">
    <title>MIDAS- Prints:: {{$title}}</title>
    <script language="Javascript1.2">
        function printpage() {
            window.print();
            }
    </script>
</head>

<body onload="printpage()">
    <div class="midas-container">
        <header class="header">
            <section class="midas-item-container">
                <img src="{{asset('images/logo2.png')}}" alt="" class="logo">
                <div class="midas-item-wrapper">
                    <span class="profile-item">1 Hospital Road, Mission Ward</span>
                    <span class="profile-item">Makurdi, Benue State</span>
                    <span class="profile-item">mindastouch@gmail.com</span>
                    <span class="profile-item">+234 80-900-987-090</span>
                </div>
            </section>

            <section class="statement-notification">
                <span class="profile-name">Period</span>
                <span class="profile-item">From: {{$from}}</span>
                <span class="profile-item">To: {{$to}}</span>
            </section>
        </header>
        <section class="statement-title">
            <h4>STATEMENT OF SAVINGS</h4>
        </section>

        <section class="header-content">
            <div class="membership-details precision-left">
                <span class="profile-item">Name: {{auth()->user()->first_name}} {{auth()->user()->last_name}}
                    {{auth()->user()->other_name}}</span>
                <span class="profile-item">Membership No: {{auth()->id()}}</span>
                <span class="profile-item">Membership Type: Ordinary</span>
                <span class="profile-item">Address: {{auth()->user()->home_add}}</span>
            </div>
            <div class="membership-details precision-right">
                <span class="profile-item">Total Debit:
                    {{number_format($Saving->totalDebit(auth()->id()),2,',','.')}}</span>
                <span class="profile-item">Total Credit:
                    {{number_format($Saving->mySavings(auth()->id())),2,',','.'}}</span>
                <span class="profile-item">Net Savings:
                    {{number_format($Saving->mySavings(auth()->id()) - $Saving->totalDebit(auth()->id())),2,',','.'}}
                </span>
            </div>
        </section>
        <section class="print-area">
            @yield('print-area')
        </section>

    </div>
    <script src="{{asset('js/app.js')}}"></script>
</body>

</html>