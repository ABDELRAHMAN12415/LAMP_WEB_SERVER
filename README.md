# LAMP Web Server Project

## Project Overview

This project demonstrates setting up a **LAMP (Linux, Apache, MySQL, PHP)** stack on an Ubuntu server. The website is configured to:

- Serve static HTML and dynamic PHP content.
- Connect to a MySQL database and display visitor data (IP and current time).
- Be publicly accessible via a cloud service provider.

## Infrastructure:
1. Creaating a key pair for estaplising ssh connection
   ![Screenshot (2334)](https://github.com/user-attachments/assets/86fb9721-86ea-4304-8b5a-6b0306db6b15)
   
2. Creating an EC2 instance 
  ![Screenshot (2335)](https://github.com/user-attachments/assets/a74fea31-db83-4162-b07b-5bbb1c58ce7b)
  ![Screenshot (2336)](https://github.com/user-attachments/assets/299cb947-66c7-49aa-be5b-409754fa1cb9)
i attached the Key pair i'd just made and also configured the security group rules in this step, allowing ssh, http and https inpound traffic.
**Auto-assign public ip address is enabled so that it can be accessable externally**

4. connecting to the EC2 via ssh
   ```bash
   ssh -i /path/to/your-key.pem ubuntu@<your-ec2-public-ip>
   ```
On Windows, use an SSH client like **PuTTY**

## Setup and Installation

1. **Set up the LAMP stack**:

   - Install **Apache**, **MySQL**, and **PHP**:
   
     ```bash
     sudo apt update
     sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql
     ```
2. **Configure Apache**:
   o Ensure that the server is configured to serve the website from the /var/www/html/ 
   directory. 
   ![Screenshot (2341)](https://github.com/user-attachments/assets/df8f7751-2f4b-415f-bd7a-03221f0dd9c7)
   o Test by creating a simple index.html in /var/www/html/ and ensuring it is accessible via 
   http://<server-ip>/.
   ![Screenshot (2345)](https://github.com/user-attachments/assets/dab5ec53-f280-4df5-872a-d12c0b471abf)
   ![Screenshot (2344)](https://github.com/user-attachments/assets/5bc85527-9591-49d2-b047-f8b7f7b09925)

3. **Create a Simple Website**:
   o Replace index.html with a PHP file (e.g. index.php) that displays "Hello World!".
   ![Screenshot (2351)](https://github.com/user-attachments/assets/40432bbe-b685-4131-a7ee-1d5ef6ad7872)
   o Verify this by accessing http://<server-ip>/ in a web browser.
   ![Screenshot (2349)](https://github.com/user-attachments/assets/e3957bae-7ec5-4b94-b938-f6d88d9c6b12)
**note**: this step requires either editing the apache server configurations to make serving the .php files a higher priority over the .html files or simply deleting the html file.
   ![Screenshot (2348)](https://github.com/user-attachments/assets/0bc2ec79-89b8-46f3-92a9-05073f48f9dc)
   edit this file from this:![Screenshot (2346)](https://github.com/user-attachments/assets/a604fff7-1c44-4d60-b837-6911724410d3)
   to this:![Screenshot (2347)](https://github.com/user-attachments/assets/65ff2b75-55d5-4f4c-ac89-818617334823)
4. **Configure MySQL**:
   o Secure the MySQL installation (mysql_secure_installation or similar).
   ![Screenshot (2352)](https://github.com/user-attachments/assets/62fa7fe4-42d1-4c09-8f84-cd50b0cbf917)

   o Create a new database (e.g. web_db) and a new MySQL user with a password.
   ![Screenshot (2353)](https://github.com/user-attachments/assets/67de940a-f61a-4dac-986f-d6efce2d431f)

   - additionally Create a table to store visitor data:

     ```bash
     mysql -u web_user -p
     USE web_db;
     CREATE TABLE visitor_data (
         id INT AUTO_INCREMENT PRIMARY KEY,
         ip_address VARCHAR(39),
         visit_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );
     ```

5. **Modify the Website to Use the Database:**:

   - Edit `index.php` file in the `/var/www/html/` directory to connect to MySQL and display data:
   
     ```php
     <?php
     // Include the config file to get the database credentials
     include('config.php');
     
     // Create a connection to the MySQL database using the credentials from config.php
     $conn = new mysqli($servername, $username, $password, $dbname);

     // Check connection
     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     }

     // Get the visitor's IP address and current time
     $visitor_ip = $_SERVER['REMOTE_ADDR'];
     $current_time = date("Y-m-d H:i:s");

     // Insert the visitor data into the table
     $sql = "INSERT INTO visitor_data (ip_address, visit_time) VALUES ('$visitor_ip', '$current_time')";
     if ($conn->query($sql) === TRUE) {
       // Data inserted successfully
     } else {
       echo "Error: " . $sql . "<br>" . $conn->error;
     }

     // Display the message with the visitor's IP and current time
     echo "Hello World!<br>";
     echo "Your IP address is: " . $visitor_ip . "<br>";
     echo "Current time is: " . $current_time . "<br>";
     
     $conn->close();
     ?>
     ```
  - i added the sensetive data as a separate config.php file
    ```php
    <?php
    $servername = "localhost";   // For MySQL running on the same server
    $username = "web_user";      // The username created for MySQL
    $password = "StrongPassword123";  // The password for the MySQL user
    $dbname = "web_db";          // The name of the MySQL database
    ?>
    ```
**note**: Sensetive data is shown for illustration.


## Networking Basics

This section explains the key networking concepts that are crucial for understanding how devices communicate within a network and how remote access to cloud-based instances is achieved.

1. IP Address

**What is an IP Address?**  
An **IP address** (Internet Protocol address) is a unique numerical identifier assigned to each device connected to a network. It is used to identify the source and destination of data packets as they travel across a network, ensuring that information reaches the correct device.

**Purpose in Networking:**  
The main purpose of an IP address is to facilitate communication between devices in a network, whether local (LAN) or across the internet (WAN). It allows devices to send and receive data by directing packets to the correct device.

**Types of IP Addresses:**  
- **IPv4**: The most commonly used version of IP addresses, represented in four sets of numbers (e.g., `192.168.1.1`).
- **IPv6**: A newer version designed to solve the limitations of IPv4, represented in eight groups of hexadecimal numbers (e.g., `2001:0db8:85a3:0000:0000:8a2e:0370:7334`).

2. MAC Address

**What is a MAC Address?**  
A **MAC address** (Media Access Control address) is a unique identifier assigned to network interfaces for communication on the physical network layer. It is embedded in hardware (such as network cards) by the manufacturer.

**Purpose in Networking:**  
The MAC address serves as a unique identifier for devices at the data link layer, ensuring that data is correctly addressed to and from a particular network interface within the local network.

**How it Differs from an IP Address:**  
While an IP address is used for identifying devices on a network at higher layers (network layer), the MAC address works at the data link layer. The key difference is that an IP address can change based on network configuration (e.g., DHCP), but a MAC address is fixed and hardware-specific.

3. Switches, Routers, and Routing Protocols

### Switches

**Definition:**  
A **switch** is a networking device that connects multiple devices within the same local network (LAN). It works at the data link layer (Layer 2) and forwards data frames based on MAC addresses.

**Role in a Network:**  
The main function of a switch is to receive data packets from devices, process them, and then forward them to the correct device on the network. It helps ensure that devices on a local network can communicate efficiently.

### Routers

**Definition:**  
A **router** is a device that connects different networks, such as local networks (LANs) to the internet or other remote networks. It operates at the network layer (Layer 3) and forwards data packets between networks based on IP addresses.

**Role in a Network:**  
Routers direct data traffic from one network to another, ensuring that packets reach their correct destination. For example, it forwards traffic from your home network to the internet. Routers also perform Network Address Translation (NAT), which allows multiple devices on a local network to share a single public IP address.

### Routing Protocols

**Definition:**  
Routing protocols are used by routers to determine the best path for data to travel between networks. They dynamically update routing tables to reflect changes in the network topology.

**Examples of Routing Protocols:**
- **RIP** (Routing Information Protocol): A distance-vector protocol that uses hop count as a metric.
- **OSPF** (Open Shortest Path First): A link-state protocol that uses the cost of links to determine the best path.
- **BGP** (Border Gateway Protocol): The protocol used to exchange routing information between different autonomous systems (AS) on the internet.

4. Remote Connection to Cloud Instance

### Steps to Connect to a Cloud-Based Linux Instance via SSH

To connect to a cloud-based Linux instance (e.g., AWS EC2) from a remote machine, follow these steps:

Remote connections to cloud-based instances typically use **SSH (Secure Shell)**, a secure protocol for accessing remote machines over an unsecured network. SSH uses key pairs for authentication: the **public key** is stored on the cloud instance, and the **private key** stays on your local machine.
- so you can either create the keys locally and copy the public key to the ec2 instanse or you can create the keys aas key pair in aws and download the private key to your device and attach the other to the ec2.
Next step to connect, you need the **public IP address** of your instance and must ensure **port 22** is open through security settings like **firewall rules** or **security groups**.

   ```bash
   ssh -i /path/to/your-key.pem ubuntu@<your-ec2-public-ip>
   ```
