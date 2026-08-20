## Laravel Task Manager

A basic task manager employing CRUD principles to create, display, edit and delete tasks - code generated/tutorial by Claude. 

- Uses Laravel migrations to create a basic DB schema. 
- Routing used to display Blade views for each functionality. 

### Edit 1
- User login and registration added with new Users table migration
- Tasks are linked and filtered by user with foreign key
- Middleware used to restrict app routes to only logged in users
