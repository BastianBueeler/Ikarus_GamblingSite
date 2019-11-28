DROP SCHEMA IF EXISTS `ikarusgamblingsite`;

CREATE SCHEMA IF NOT EXISTS `ikarusgamblingsite` DEFAULT CHARACTER SET utf8;
USE `ikarusgamblingsite`;

CREATE TABLE personstatistic
(
    ID                    int not null AUTO_INCREMENT,
    CountedBlackJackGames int,
    CountedRouletteGames  int,
    BlackJackWins         int,
    RouletteWins          int,
    MoneyWonBlackJack     int,
    MoneyWonRoulette      int,
    MoneySpentBlackJack   int,
    MoneySpentRoulette    int,
    primary key (ID)
);

CREATE TABLE person
(
    Username     varchar(30)  not null,
    fk_statistic int          not null,
    Password     varchar(255) not null,
    EMail        varchar(40)  not null,
    Name         varchar(30)  not null,
    PreName      varchar(30)  not null,
    IkarusCoins  int          not null,
    primary key (Username),
    FOREIGN KEY (fk_statistic) REFERENCES personstatistic (ID)
);

