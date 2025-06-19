<?php
          $env = parse_ini_file(__DIR__ . '/../.env');

          $conn = new mysqli(
              $env['DB_HOST'],
              $env['DB_USER'],
              $env['DB_PASS'],
              $env['DB_NAME'],
              $env['DB_PORT']
          );

          // Check connection
          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
          }

          // Set charset to utf8mb4
          $conn->set_charset("utf8mb4");
            
          ?>