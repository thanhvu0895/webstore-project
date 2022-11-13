<style>
    .footer {
        width: 100%;
        height: 22vh;
        padding: 20px;
        display: flex;
        background-color: orange;
        flex-direction: row;
        overflow-y: hidden;
    }

    .footer p {
        margin: 0;
    }

    .footer-about,
    .footer-category {
        width: 300px;
        display: flex;
        flex-direction: column;
        margin-right: 5vw;
    }

    .footer-about a {
        text-decoration: none;
        color: black;
    }

    .footer-about-line,
    .footer-category-line {
        border-top: 2px solid black;
        margin-bottom: 10px;
    }

    .footer-social {
        width: 70%;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: space-between;
    }

    .footer-social-logos {
        display: flex;
        flex-direction: row;
    }

    .footer-social-logo-item {
        width: 36px;
        height: 36px;
        margin-right: 20px;
        border-radius: 50%;
    }

    @media screen and (max-width: 720px) {
        .footer p {
            font-size: 12px;
        }

        .footer h6 {
            font-size: 14px;
        }

        .footer-social {
            display: none;
        }
    }
</style>

<div class="footer">
    <div class="footer-category">
        <h6>Category</h6>
        <div class="footer-category-line"></div>
        <p>Office Supplies</p>
        <p>Books</p>
        <p>Electronics</p>
        <p>Home Goods</p>
    </div>
    <div class="footer-about">
        <h6>About</h6>
        <div class="footer-about-line"></div>
        <p><a href="about.php">About us</a></p>
        <?php

        echo (isset($_SESSION["email"])) ? "<a href=refill-webcoins.php> <p>Refill Webcoins</p></a>" : "";
        ?>
    </div>
    <div class="footer-social"></div>
    <div class="footer-social-copyright">
        <p>© 2022 Web development Group 2</p>
    </div>
</div>