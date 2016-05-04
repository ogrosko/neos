<?php
namespace TYPO3\Flow\Resource\Streams;

/*
 * This file is part of the TYPO3.Flow package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

/**
 * A stream wrapper interface. Modeled after the PHP streamWrapper class
 * prototype. Check http://php.net/streamwrapper for details on that.
 *
 * We divert from the PHP prototype in the following:
 *
 *  * better method names
 *  * methods that should not be implemented in the PHP prototype when not being
 *    supported (like mkdir) must throw a \BadMethodCallException instead.
 *
 * @api
 */
interface StreamWrapperInterface
{
    /**
     * Returns the scheme ("protocol") this wrapper handles.
     *
     * @return string
     * @api
     */
    public static function getScheme();

    /**
     * Close directory handle.
     *
     * This method is called in response to closedir().
     *
     * Any resources which were locked, or allocated, during opening and use of
     * the directory stream should be released.
     *
     * @return boolean TRUE on success or FALSE on failure.
     * @api
     */
    public function closeDirectory();

    /**
     * Open directory handle.
     *
     * This method is called in response to opendir().
     *
     * @param string $path Specifies the URL that was passed to opendir().
     * @param int $options Whether or not to enforce safe_mode (0x04).
     * @return boolean TRUE on success or FALSE on failure.
     * @api
     */
    public function openDirectory($path, $options);

    /**
     * Read entry from directory handle.
     *
     * This method is called in response to readdir().
     *
     * @return string Should return string representing the next filename, or FALSE if there is no next file.
     * @api
     */
    public function readDirectory();

    /**
     * Rewind directory handle.
     *
     * This method is called in response to rewinddir().
     *
     * Should reset the output generated by dir_readdir(). I.e.: The next call
     * to dir_readdir() should return the first entry in the location returned
     * by dir_opendir().
     *
     * @return boolean TRUE on success or FALSE on failure.
     * @api
     */
    public function rewindDirectory();

    /**
     * Create a directory.
     *
     * This method is called in response to mkdir().
     *
     * Note: If the wrapper does not support creating directories it must throw
     * a \BadMethodCallException.
     *
     * @param string $path Directory which should be created.
     * @param integer $mode The value passed to mkdir().
     * @param integer $options A bitwise mask of values, such as STREAM_MKDIR_RECURSIVE.
     * @return boolean TRUE on success or FALSE on failure.
     * @api
     */
    public function makeDirectory($path, $mode, $options);

    /**
     * Removes a directory.
     *
     * This method is called in response to rmdir().
     *
     * Note: If the wrapper does not support creating directories it must throw
     * a \BadMethodCallException.
     *
     * @param string $path The directory URL which should be removed.
     * @param integer $options A bitwise mask of values, such as STREAM_MKDIR_RECURSIVE.
     * @return boolean TRUE on success or FALSE on failure.
     * @api
     */
    public function removeDirectory($path, $options);

    /**
     * Renames a file or directory.
     *
     * This method is called in response to rename().
     *
     * Should attempt to rename path_from to path_to.
     *
     * @param string $source The URL to the current file.
     * @param string $target The URL which the path_from should be renamed to.
     * @return boolean TRUE on success or FALSE on failure.
     * @api
     */
    public function rename($source, $target);

    /**
     * Retrieve the underlying resource.
     *
     * This method is called in response to stream_select().
     *
     * @param integer $castType Can be STREAM_CAST_FOR_SELECT when stream_select() is calling stream_cast() or STREAM_CAST_AS_STREAM when stream_cast() is called for other uses.
     * @return resource Should return the underlying stream resource used by the wrapper, or FALSE.
     * @api
     */
    public function cast($castType);

    /**
     * Close an resource.
     *
     * This method is called in response to fclose().
     *
     * All resources that were locked, or allocated, by the wrapper should be
     * released.
     *
     * @return void
     * @api
     */
    public function close();

    /**
     * Tests for end-of-file on a file pointer.
     *
     * This method is called in response to feof().
     *
     * @return boolean Should return TRUE if the read/write position is at the end of the stream and if no more data is available to be read, or FALSE otherwise.
     * @api
     */
    public function isAtEof();

    /**
     * Flushes the output.
     *
     * This method is called in response to fflush().
     *
     * If you have cached data in your stream but not yet stored it into the
     * underlying storage, you should do so now.
     *
     * Note: If not implemented, FALSE is assumed as the return value.
     *
     * @return boolean Should return TRUE if the cached data was successfully stored (or if there was no data to store), or FALSE if the data could not be stored.
     * @api
     */
    public function flush();

    /**
     * Advisory file locking.
     *
     * This method is called in response to flock(), when file_put_contents()
     * (when flags contains LOCK_EX), stream_set_blocking().
     *
     * $operation is one of the following:
     *  LOCK_SH to acquire a shared lock (reader).
     *  LOCK_EX to acquire an exclusive lock (writer).
     *  LOCK_NB if you don't want flock() to block while locking.
     *
     * @param integer $operation One of the LOCK_* constants
     * @return boolean TRUE on success or FALSE on failure.
     * @api
     */
    public function lock($operation);

    /**
     * Advisory file locking.
     *
     * This method is called when closing the stream (LOCK_UN).
     *
     * @return boolean TRUE on success or FALSE on failure.
     * @api
     */
    public function unlock();

    /**
     * Opens file or URL.
     *
     * This method is called immediately after the wrapper is initialized (f.e.
     * by fopen() and file_get_contents()).
     *
     * $options can hold one of the following values OR'd together:
     *  STREAM_USE_PATH
     *    If path is relative, search for the resource using the include_path.
     *  STREAM_REPORT_ERRORS
     *    If this flag is set, you are responsible for raising errors using
     *    trigger_error() during opening of the stream. If this flag is not set,
     *    you should not raise any errors.
     *
     * @param string $path Specifies the URL that was passed to the original function.
     * @param string $mode The mode used to open the file, as detailed for fopen().
     * @param integer $options Holds additional flags set by the streams API.
     * @param string &$openedPathAndFilename path If the path is opened successfully, and STREAM_USE_PATH is set in options, opened_path should be set to the full path of the file/resource that was actually opened.
     * @return boolean TRUE on success or FALSE on failure.
     * @api
     */
    public function open($path, $mode, $options, &$openedPathAndFilename);

    /**
     * Read from stream.
     *
     * This method is called in response to fread() and fgets().
     *
     * Note: Remember to update the read/write position of the stream (by the
     * number of bytes that were successfully read).
     *
     * @param integer $count How many bytes of data from the current position should be returned.
     * @return string If there are less than count bytes available, return as many as are available. If no more data is available, return either FALSE or an empty string.
     * @api
     */
    public function read($count);

    /**
     * Seeks to specific location in a stream.
     *
     * This method is called in response to fseek().
     *
     * The read/write position of the stream should be updated according to the
     * offset and whence .
     *
     * $whence can one of:
     *  SEEK_SET - Set position equal to offset bytes.
     *  SEEK_CUR - Set position to current location plus offset.
     *  SEEK_END - Set position to end-of-file plus offset.
     *
     * @param integer $offset The stream offset to seek to.
     * @param integer $whence
     * @return boolean TRUE on success or FALSE on failure.
     * @api
     */
    public function seek($offset, $whence = SEEK_SET);

    /**
     * Change stream options.
     *
     * This method is called to set options on the stream.
     *
     * $option can be one of:
     *  STREAM_OPTION_BLOCKING (The method was called in response to stream_set_blocking())
     *  STREAM_OPTION_READ_TIMEOUT (The method was called in response to stream_set_timeout())
     *  STREAM_OPTION_WRITE_BUFFER (The method was called in response to stream_set_write_buffer())
     *
     * If $option is ... then $arg1 is set to:
     *  STREAM_OPTION_BLOCKING: requested blocking mode (1 meaning block 0 not blocking).
     *  STREAM_OPTION_READ_TIMEOUT: the timeout in seconds.
     *  STREAM_OPTION_WRITE_BUFFER: buffer mode (STREAM_BUFFER_NONE or STREAM_BUFFER_FULL).
     *
     * If $option is ... then $arg2 is set to:
     *  STREAM_OPTION_BLOCKING: This option is not set.
     *  STREAM_OPTION_READ_TIMEOUT: the timeout in microseconds.
     *  STREAM_OPTION_WRITE_BUFFER: the requested buffer size.
     *
     * @param integer $option
     * @param integer $argument1
     * @param integer $argument2
     * @return boolean TRUE on success or FALSE on failure. If option is not implemented, FALSE should be returned.
     * @api
     */
    public function setOption($option, $argument1, $argument2);

    /**
     * Retrieve the current position of a stream.
     *
     * This method is called in response to ftell().
     *
     * @return int Should return the current position of the stream.
     * @api
     */
    public function tell();

    /**
     * Write to stream.
     *
     * This method is called in response to fwrite().
     *
     * If there is not enough room in the underlying stream, store as much as
     * possible.
     *
     * Note: Remember to update the current position of the stream by number of
     * bytes that were successfully written.
     *
     * @param string $data Should be stored into the underlying stream.
     * @return int Should return the number of bytes that were successfully stored, or 0 if none could be stored.
     * @api
     */
    public function write($data);

    /**
     * Delete a file.
     *
     * This method is called in response to unlink().
     *
     * Note: In order for the appropriate error message to be returned this
     * method should not be defined if the wrapper does not support removing
     * files.
     *
     * @param string $path The file URL which should be deleted.
     * @return boolean TRUE on success or FALSE on failure.
     * @api
     */
    public function unlink($path);

    /**
     * Retrieve information about a file resource.
     *
     * This method is called in response to fstat().
     *
     * @return array See http://php.net/stat
     * @api
     */
    public function resourceStat();

    /**
     * Retrieve information about a file.
     *
     * This method is called in response to all stat() related functions.
     *
     * $flags can hold one or more of the following values OR'd together:
     *  STREAM_URL_STAT_LINK
     *     For resources with the ability to link to other resource (such as an
     *     HTTP Location: forward, or a filesystem symlink). This flag specified
     *     that only information about the link itself should be returned, not
     *     the resource pointed to by the link. This flag is set in response to
     *     calls to lstat(), is_link(), or filetype().
     *  STREAM_URL_STAT_QUIET
     *     If this flag is set, your wrapper should not raise any errors. If
     *     this flag is not set, you are responsible for reporting errors using
     *     the trigger_error() function during stating of the path.
     *
     * @param string $path The file path or URL to stat. Note that in the case of a URL, it must be a :// delimited URL. Other URL forms are not supported.
     * @param integer $flags Holds additional flags set by the streams API.
     * @return array Should return as many elements as stat() does. Unknown or unavailable values should be set to a rational value (usually 0).
     * @api
     */
    public function pathStat($path, $flags);
}
