<script>
	import LoginForm from "../../Components/LoginForm.svelte";

	// your script goes here

	let reason = false;

	async function click_handler(login, password) {
		let newForm = new FormData();
		newForm.set("login", login);
		newForm.set("password", password);

		let res = await fetch("./php/register.php", {
			method: "POST",
			body: newForm,
		});

		let data = await res.json();

		console.log(data);
		if (data.code == -1) {
			reason = true;
		} else {
			window.location.href = "./#/login";
		}
	}
</script>

<LoginForm
	{reason}
	clickHandler={click_handler}
	title_text="Sign up"
	footer_text="Log in"
   link = "login"
   reason_text="The given login already exists, or you are already logged in"
/>

<style>
	/* your styles go here */
</style>
