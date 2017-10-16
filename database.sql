DROP TABLE users;
DROP TABLE stocks;
DROP TABLE transactions;
DROP TABLE user_stocks;

CREATE TABLE users(name VARCHAR(20), username VARCHAR(20) PRIMARY KEY, password VARCHAR(20), cash DECIMAL(10, 2), dow30_value DECIMAL(10, 2), overseas_value DECIMAL(10, 2));
CREATE TABLE stocks(stock_name VARCHAR(20), symbol VARCHAR(20) PRIMARY KEY, sept_price DECIMAL(10, 2), sept_price_overseas DECIMAL(10, 2));
CREATE TABLE transactions(type ENUM('Deposit Cash', 'Withdraw Cash', 'Buy', 'Sell'), username VARCHAR(20), symbol VARCHAR(20), time_stamp TIMESTAMP, shares NUMERIC(10), price DECIMAL(10, 2), cash_value DECIMAL(10, 2), foreign_cash_value DECIMAL(10, 2));
CREATE TABLE user_stocks(username VARCHAR(20), symbol VARCHAR(20), shares NUMERIC(20), cost_basis DECIMAL(10, 2), category ENUM('dow30', 'overseas'));

INSERT INTO users VALUES("a", "a", "a", 1000, 0, 0);

INSERT INTO stocks VALUES ("Apple Inc.", "AAPL", "164.05", "0");
INSERT INTO stocks VALUES ("Microsoft Corp.", "MSFT", "73.94", "0");
INSERT INTO stocks VALUES ("The Walt Disney Co.", "DIS", "101.50", "0");
INSERT INTO stocks VALUES ("IBM", "IBM", "144.08", "0");
INSERT INTO stocks VALUES ("Nike Inc.", "NKE", "53.36", "0");
INSERT INTO stocks VALUES ("Pfizer Inc.", "PFE", "33.96", "0");

INSERT INTO stocks VALUES ("Axis Bank Ltd", "TATAMOTORS.NS", "50", "390.85");
INSERT INTO stocks VALUES ("Bharti Airtel Ltd.", "BHARTIARTL.NS", "50", "422.55");
INSERT INTO stocks VALUES ("TCS Ltd.", "TCS.NS", "50", "2456.45");
INSERT INTO stocks VALUES ("Kotak Mahindra Ltd.", "KOTAKBANK.NS", "50", "992.55");

SELECT * FROM users;
SELECT * FROM transactions;
SELECT * FROM user_stocks;
