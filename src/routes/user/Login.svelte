<script>
	import LoginForm from "../../Components/LoginForm.svelte";
	import { userUpdate } from "../../config/userInfo";

	// your script goes here

	let reason = false;

	async function click_handler(login, password) {
		let newForm = new FormData();
		newForm.set("login", login);
		newForm.set("password", password);

		let res = await fetch("./php/login.php", {
			method: "POST",
			body: newForm,
		});

		let data = await res.json();

		console.log(data);
		if (data.code == -1) {
			reason = true;
		} else {
			userUpdate();
			window.location.href = "./#/";
		}
	}
</script>

<LoginForm
	{reason}
	clickHandler={click_handler}
	title_text="Log in"
	footer_text="Sign up"
	link="register"
	reason_text="Wrong login or password, or you are already logged in, or your account has not been activated"
/>

<style>
	/* your styles go here */
</style>
