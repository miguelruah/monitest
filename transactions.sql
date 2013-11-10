CREATE TABLE transactions (
  user int(3) DEFAULT NULL,
  date date DEFAULT NULL,
  amount decimal(9,2) DEFAULT NULL,
  currency varchar(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO transactions (user, date, amount, currency) VALUES
(1, '2013-07-12', '123.00', 'PLN'),
(1, '2013-07-13', '10.00', 'PLN'),
(2, '2013-01-09', '100.00', 'EUR'),
(3, '2013-11-23', '77.00', 'GBP'),
(1, '2013-03-13', '500.00', 'USD'),
(3, '2013-07-22', '150.00', 'EUR'),
(2, '2013-05-01', '130.00', 'PLN');

