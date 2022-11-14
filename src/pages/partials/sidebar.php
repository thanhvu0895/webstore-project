<style>
  .sidebar ul {
    min-width: 150px;
    display: flex;
    flex-direction: column;
    list-style: none;
    border-radius: 6px;
    background-color: white;
    padding: 0px;
    border: 1px solid black;
  }

  .sidebar li {
    padding: 10px;
    border-bottom: 1px solid #e6e6e6;
  }

  .sidebar li a {
    text-decoration: none;
    color: black;
  }

  .catalog p {
    margin: 0px;
  }

  @media screen and (max-width: 720px) {
    .sidebar ul {
      min-width: 250px;
    }
  }
</style>

<div class="sidebar">
  <ul>
    <li><a href="home.php">Account</a></li>
    <li><a href="wishlist.php">Wishlist</a></li>
    <li><a href="cart.php">Cart</a></li>
    <li><a href="order.php">Order History</a></li>
    <li><a href="refill-webcoins.php">Refill Webcoins</a></li>
  </ul>
</div>