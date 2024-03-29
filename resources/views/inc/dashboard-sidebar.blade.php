<nav class="sidebar">
    <ul class="side-nav">
        <li class="side-nav__item side-nav__item--active">
            <a href="#" class="side-nav__link">
                <svg class="side-nav__icon">
                    <use xlink:href="{{asset('images/sprite.svg#icon-home')}}"></use>
                </svg>
                <span>Home</span>
            </a>
        </li>
        <li class="side-nav__item">
            <a href="/Dashboard/user/savingsummary" class="side-nav__link">
                <svg class="side-nav__icon">
                    <use xlink:href="{{asset('images/sprite.svg#icon-people')}}"></use>
                </svg>
                <span>Saving</span>
            </a>
        </li>
        <li class="side-nav__item">
            <a href="/Dashboard/user/targetsavings" class="side-nav__link">
                <svg class="side-nav__icon">
                    <use xlink:href="{{asset('images/sprite.svg#icon-database')}}"></use>
                </svg>
                <span>Target Saving</span>
            </a>
        </li>
        {{--
            <li class="side-nav__item">
                <a href="#" class="side-nav__link">
                    <svg class="side-nav__icon">
                            <use xlink:href="{{asset('images/sprite.svg#icon-feather')}}"></use>
        </svg>
        <span>Savings Plus</span>
        </a>
        </li> --}}
        <li class="side-nav__item">
            <a href="/Dashboard/user/loans" class="side-nav__link">
                <svg class="side-nav__icon">
                    <use xlink:href="{{asset('images/sprite.svg#icon-layers')}}"></use>
                </svg>
                <span>My Loans</span>
            </a>
        </li>
        <li class="side-nav__item">
            <a href="/Dashboard/user/schemes" class="side-nav__link">
                <svg class="side-nav__icon">
                    <use xlink:href="{{asset('images/sprite.svg#icon-shopping_cart')}}"></use>
                </svg>
                <span>Schemes</span>
            </a>
        </li>
    </ul>
    <div class="legal">
        &copy; 2019 by Midas. All rights reserved.
    </div>
</nav>