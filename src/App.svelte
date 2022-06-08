<script>
	import Router from "svelte-spa-router";
	import { wrap } from "svelte-spa-router/wrap";	
	import Tailwindcss from "./Tailwindcss.svelte";

	import Header from "./Components/Header.svelte";

	import Home from "./routes/Home.svelte";
	import Login from "./routes/user/Login.svelte";
	import Register from "./routes/user/Register.svelte";
	import Offers from "./routes/offers/Offers.svelte";
	import Users from "./routes/admin/Users.svelte";
	import Applications from "./routes/moder/Applications.svelte";
	import YourOrders from "./routes/offers/reservations/YourOrders.svelte";
	import Order from "./routes/offers/reservations/Order.svelte";
	import MainSheduler from "./routes/moder/sheduler/mainSheduler.svelte";
	import RuleBook from "./routes/RuleBook.svelte";
	import Footer from "./Components/Footer.svelte";
	import DEFAULT from "./DEFAULT.svelte";
</script>

<Tailwindcss />

<div class="all-container">
	<div>
		<Header />

		<Router
			routes={{
				"/": Home,
				"/login": Login,
				"/register": Register,
								
				"/offers": Offers,
				"/your_orders": YourOrders,
				"/your_orders/:orderType/:orderId": Order,
				// admin|moder
				"/users": Users,
				"/applications": Applications,
				"/sheduler": MainSheduler,
				"/regulamin": RuleBook,
				"*": DEFAULT,
			}}
		/>
	</div>

	<Footer />
</div>
<h1 class="text-red-600"></h1>

<style>
	.all-container {
		min-height: 100%;
		display: grid;
		grid-template-rows: auto min-content;
	}
</style>
