const Router = require('express').Router;
module.exports = Router({ mergeParams: true }).post('/v1/links/create', async (req, res, next) => {
	try {
		// const user = new req.db.User({
		//     username: req.body.username,
		//     password: req.body.password,
		// })
		// await user.save()
		// const location = ${req.base}${req.originalUrl}/${user.id}`
		var body = req.body;
		console.log(body);
		//res.setHeader('Location', location)
		res.status(201).send(body);
	} catch (error) {
		next(error);
	}
});