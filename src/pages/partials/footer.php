<style>
    .footer {
        width: 100%;
        height: 30vh;
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
        width: 100%;
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
</style>

<div class="footer">
    <div class="footer-category">
        <h6>Shop by Category</h6>
        <div class="footer-category-line"></div>
        <p>Office Supplies</p>
        <p>Books</p>
        <p>Electronics</p>
        <p>Home Goods</p>
    </div>
    <div class="footer-about">
        <h6>About</h6>
        <div class="footer-about-line"></div>
        <a href="about.php">About us</a>
        <p>Refill Webcoins</p>
    </div>
    <div class="footer-social">
        <div class="footer-social-logos">
            <div class="footer-social-logo-item">
                <img src="../images/fb.png" alt="fb-icon" />
            </div>
            <div class="footer-social-logo-item">
                <img src="../images/insta-logo.png" alt="insta-icon" />
            </div>
            <div class="footer-social-logo-item">
                <img src="../images/youtube.png" alt="youtube" />
            </div>
            <div class="footer-social-logo-item">
                <img src="../images/twitter.png" alt="twitter" />
            </div>
        </div>
        <div class="footer-social-copyright">
            © 2022 | Web development | Group 2
        </div>
    </div>
</div>