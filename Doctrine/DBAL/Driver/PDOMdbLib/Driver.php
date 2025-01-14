<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace PDOAccess\PDOAccessBundle\Doctrine\DBAL\Driver\PDOMdbLib;

/**
 * The PDO-based Dblib driver.
 *
 * @since 2.0
 */
class Driver implements \Doctrine\DBAL\Driver {
	public function connect(array $params, $username = null, $password = null, array $driverOptions = array()) {
		return new Connection(
			$this->_constructPdoDsn($params),
			$username,
			$password,
			$driverOptions
		);
	}

	/**
	 * Constructs the Dblib PDO DSN.
	 *
	 * @return string  The DSN.
	 */
	private function _constructPdoDsn(array $params) {

        $dsn = 'odbc:';
        
        if (isset($params['host'])) {
            $dsn .= "Driver=" . $params['host'];
		}
        
		return $dsn;
	}

	public function getDatabasePlatform() {
		return new \Doctrine\DBAL\Platforms\SQLServerPlatform();
		//return new \Doctrine\DBAL\Platforms\SQLServer2005Platform();
	}

	public function getSchemaManager(\Doctrine\DBAL\Connection $conn, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        assert($platform instanceof AbstractMySQLPlatform);

		return new \Doctrine\DBAL\Schema\MsSqlSchemaManager($conn,$platform);
		//return new \Doctrine\DBAL\Schema\SQLServerSchemaManager($conn);
	}

	public function getName() {
		return 'pdo_mdblib';
	}

	public function getDatabase(\Doctrine\DBAL\Connection $conn) {
		$params = $conn->getParams();
		return $params['dbname'];
	}

	public function getExceptionConverter(): \Doctrine\DBAL\Driver\API\ExceptionConverter
    {
        return new \Doctrine\DBAL\Driver\API\MySQL\ExceptionConverter();
    }
}