## Laravel Task Manager

A basic task manager employing CRUD principles to create, display, edit and delete tasks - code generated/tutorial by Claude. 

- Uses Laravel migrations to create a basic DB schema. 
- Routing used to display Blade views for each functionality. 

### Edit 1
- User login and registration added with new Users table migration
- Tasks are linked and filtered by user with foreign key
- Middleware used to restrict app routes to only logged in users


### Edit 2
- Email setup with Mailtrap to test new user verification emails, welcome email and password reset emails
- Added these tasks as background jobs in the queue by changing .env file
- Verification email and password reset emails pass token in URL inside email, both of which have expiry limits (24h and 1h).  