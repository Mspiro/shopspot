
Welcome to Shopspot.  
This site is build only for learning purpose.
Here is some information around how to clone/setup site on your system. Also some screenshots are added.
#### Note: Shopspot is build on the Drupal platform with lando development tool.  

## How to Setup  
Step 1: Clone the repository in to your selected folder.  
Step 2: Change your directory to shopspot and in terminal run `lando start` command.
![image](https://user-images.githubusercontent.com/32816725/159829501-b12de9cc-a8fd-40ba-95b5-d523d6de8698.png)  
Now you see the above information.  
Step 3: Now go th the PHPMYADMIN Localhost url.    
![image](https://user-images.githubusercontent.com/32816725/159829758-ce62e9f1-a329-48ec-9b79-88c51ba793b1.png)  
Step 4: Here import database file backup.sql from our current directory.  
![image](https://user-images.githubusercontent.com/32816725/159829953-9e9d019a-c447-4eb0-9533-203ed69eb593.png)  
Step 5: On terminal run command `lando composer install`.  
Step 6: After installing all required module run `lando drush updb` and `lando drush cim` command.  
Now the site is ready and looks like the following:  

## Screenshot:  
### Home Page:  
![Screenshot from 2022-03-24 08-03-16](https://user-images.githubusercontent.com/32816725/159830516-adffd613-d04b-4839-89e2-33c52d883815.png)

### Login Page:  
![Screenshot from 2022-03-24 08-04-58](https://user-images.githubusercontent.com/32816725/159830730-b04c3cc5-3d1b-46e4-adbf-962a7cab033a.png)
 
### Register Page:  
![Screenshot from 2022-03-24 08-06-02](https://user-images.githubusercontent.com/32816725/159830827-28bc55a8-c6da-409f-af88-63465f20965b.png)

### Login Menu:  
![Screenshot from 2022-03-24 08-08-33](https://user-images.githubusercontent.com/32816725/159831115-b0d37933-d90f-45ed-a485-9af8f2f08366.png)
  
### More Menu:  
![Screenshot from 2022-03-24 08-08-38](https://user-images.githubusercontent.com/32816725/159831147-cc2c7c1d-20ad-4d2e-8208-30a4efda65c4.png)

### User Menu:  
![Screenshot from 2022-03-24 08-09-14](https://user-images.githubusercontent.com/32816725/159831191-1f597038-fb68-4833-bb5a-739a9d41117d.png)  

### Details Page: 
![Screenshot from 2022-03-24 08-10-36](https://user-images.githubusercontent.com/32816725/159831257-f3e3788f-1c5f-4756-be1d-8173aceade03.png)  

### Product specifications:  
![Screenshot from 2022-03-24 08-11-06](https://user-images.githubusercontent.com/32816725/159831319-bdcfb839-b40a-4bb9-89c4-bdcb19a5ff32.png)  

### Product Comment: 
![Screenshot from 2022-03-24 08-12-13](https://user-images.githubusercontent.com/32816725/159831422-2499fa44-c12c-4c24-b412-6a7653add3c2.png)

### Footer Section: 
![Screenshot from 2022-03-24 08-12-53](https://user-images.githubusercontent.com/32816725/159831497-e6f41f8f-1369-43de-80a7-647605843ca2.png)

