<?php
$displayNone = (!isset($_SESSION["email"]))  ? "style='display:none'" : '';
?>
<style>
    .header {
        width: 100vw;
        height: 8vh;
        background-color: orange;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        overflow: hidden;
    }

    .header-input input {
        width: 40vw;
    }

    .header-navigation {
        display: flex;
        align-items: center;
        margin-right: 30px;
    }

    .header-navigation p {
        margin-bottom: 0;
        cursor: pointer;
        padding-left: 3px;
        margin-top: 3px;
        font-size: 15px;

    }

    .header-logo h3 a,
    .header-navigation p a {
        text-decoration: none;
        color: black;
        font-weight: 400;
    }

    .header-navigation a {
        text-decoration: none;
        color: black;
        font-weight: 400;
    }

    .header-logo h3 {
        margin: 0;
    }

    .header-logo h2 a :hover,
    .header-navigation-cart :hover,
    .header-navigation-wishlist :hover,
    .header-navigation-account :hover {
        color: rgb(164, 107, 0);
    }

    .header-navigation-cart,
    .header-navigation-wishlist,
    .header-navigation-account {
        display: flex;
        margin-left: 20px;
    }

    @media screen and (max-width: 720px) {
        .header-navigation {
            width: 100%;
            margin-right: 0;
            justify-content: flex-end;
            margin-right: 30px;
        }

        .form-control {
            width: 3vw;
        }

        .header-navigation p {
            text-align: center;
            font-size: 12px;
        }

        #header-logo {
            height: 80px;
            width: 100px;
        }
    }

    @media screen and (max-width: 560px) {
        .header-navigation {
            width: 100%;
            display: flex;
            justify-content: space-evenly;
        }

        .header-navigation p {
            display: none;
        }

        #header-logo {
            height: 90px;
            width: 120px;
        }
    }

    @media screen and (max-width: 400px) {
        .header-navigation {
            width: 150px;
            display: flex;
            justify-content: flex-end;
        }

        .header-navigation p {
            display: none;
        }

        #header-logo {
            height: 70px;
            width: 80px;
        }
    }
</style>

<!-- AVOID FORM RESUBMISSION UPON PAGE REFRESH-->
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
<!-- END SCRIPT - PAGE REFRESH-->

<header>
    <div class="header">

        <a href="index.php">
            <img id="header-logo" src='../images/KKart.png' alt="logo" height="130" width="150" />
        </a>

        <div class="header-input">
            <form action="index.php" method="post">
                <input class="form-control" name="search" type="search" placeholder="Search" aria-label="Search" />
            </form>
        </div>
        <div class="header-navigation">
            <div class="header-navigation-account">
                <?php echo (!isset($_SESSION["email"])) ? "
                <a href='signin.php'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-person-fill' viewBox='0 0 16 16'>
                        <path d='M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z' />
                    </svg>
                </a>" : "
                <a href='home.php'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-person-fill' viewBox='0 0 16 16'>
                        <path d='M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z' />
                    </svg>
                </a>"; ?>

                <?php
                echo (!isset($_SESSION["email"])) ? "<p><a href='signin.php'>Sign In</a></p>" : "<p><a href='home.php'>Account</a></p>";
                ?>
            </div>
            <div class="header-navigation-wishlist" <?php
                                                    echo $displayNone;
                                                    ?>>
                <a href="wishlist.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-bag-heart-fill" viewBox="0 0 16 16">
                        <path d="M11.5 4v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5ZM8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1Zm0 6.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132Z" />
                    </svg>
                </a>
                <p><a href="wishlist.php">Wishlist</a></p>
            </div>
            <div class="header-navigation-cart" <?php
                                                echo $displayNone;
                                                ?>>

                <a href="cart.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-cart-fill" viewBox="0 0 16 16">
                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                    </svg>
                </a>
                <p><a href="cart.php">Cart</a></p>
            </div>
        </div>
    </div>
</header>