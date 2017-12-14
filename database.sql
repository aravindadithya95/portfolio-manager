DROP TABLE transactions;
DROP TABLE user_stocks;
DROP TABLE users;
DROP TABLE stocks;

CREATE TABLE users(name VARCHAR(20), username VARCHAR(20) PRIMARY KEY, password VARCHAR(20), cash DECIMAL(10, 2), dow30_value DECIMAL(10, 2), overseas_value DECIMAL(10, 2));
CREATE TABLE stocks(stock_name VARCHAR(20), symbol VARCHAR(20) PRIMARY KEY, sept_price DECIMAL(10, 2), sept_price_overseas DECIMAL(10, 2), price DECIMAL(10, 2), price_overseas DECIMAL(10, 2), category VARCHAR(20), price_change VARCHAR(20));
CREATE TABLE transactions(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, type VARCHAR(20), username VARCHAR(20), symbol VARCHAR(20), time_stamp TIMESTAMP, shares NUMERIC(10), price DECIMAL(10, 2), price_overseas DECIMAL(10, 2), cash_value DECIMAL(10, 2), FOREIGN KEY(username) REFERENCES users(username), FOREIGN KEY(symbol) REFERENCES stocks(symbol));
CREATE TABLE user_stocks(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, username VARCHAR(20), symbol VARCHAR(20), shares NUMERIC(20), cost_basis DECIMAL(10, 2), FOREIGN KEY(username) REFERENCES users(username), FOREIGN KEY(symbol) REFERENCES stocks(symbol));

INSERT INTO users VALUES("a", "a", "a", 1000, 0, 0);

INSERT INTO stocks VALUES ("Apple Inc.", "AAPL", 164.05, 0, 10, 0, "Dow 30", "+0.57 (+0.33%)");
INSERT INTO stocks VALUES ("Microsoft Corp.", "MSFT", 73.94, 0, 10, 0, "Dow 30", "+0.57 (+0.33%)");
INSERT INTO stocks VALUES ("The Walt Disney Co.", "DIS", 101.50, 0, 10, 0, "Dow 30", "+0.57 (+0.33%)");
INSERT INTO stocks VALUES ("IBM", "IBM", "144.08", 0, 10, 0, "Dow 30", "+0.57 (+0.33%)");
INSERT INTO stocks VALUES ("Nike Inc.", "NKE", 53.36, 0, 10, 0, "Dow 30", "+0.57 (+0.33%)");
INSERT INTO stocks VALUES ("Pfizer Inc.", "PFE", 33.96, 0, 10, 0, "Dow 30", "+0.57 (+0.33%)");

INSERT INTO stocks VALUES ("Axis Bank Ltd", "TATAMOTORS.NS", 50, 390.85, 10, 100, "BSE 30", "+0.45 (+0.11%)");
INSERT INTO stocks VALUES ("Bharti Airtel Ltd.", "BHARTIARTL.NS", 50, 422.55, 10, 90, "BSE 30", "+0.45 (+0.11%)");
INSERT INTO stocks VALUES ("TCS Ltd.", "TCS.NS", 50, 2456.45, 10, 564, "BSE 30", "+0.45 (+0.11%)");
INSERT INTO stocks VALUES ("Kotak Mahindra Ltd.", "KOTAKBANK.NS", 50, 992.55, 10, 185, "BSE 30", "+0.45 (+0.11%)");

SELECT * FROM users;
SELECT * FROM transactions;
SELECT * FROM user_stocks;
SELECT * FROM stocks;

DELETE FROM user_stocks WHERE username = "a";
INSERT INTO user_stocks VALUES(NULL, "a", "AAPL", 5, 700);
INSERT INTO transactions VALUES(NULL, "Buy", "a", "AAPL", now(), 5, 140, 0, -700);
INSERT INTO user_stocks VALUES(NULL, "a", "TATAMOTORS.NS", 6, 300);
INSERT INTO transactions VALUES(NULL, "Buy", "a", "TATAMOTORS.NS", now(), 5, 50, 200, -300);
UPDATE users SET cash = 100, dow30_value = 700, overseas_value = 300 WHERE username = "a";
